<?php

namespace App\Controllers;

use App\Models\MaintenanceModel;
use App\Models\AssetModel;
use App\Models\AssetGroupModel;
use App\Models\JobTicketModel;
use App\Models\KeywordRuleModel;

class Maintenance extends BaseController
{
    protected MaintenanceModel $model;
    protected AssetModel $assetModel;
    protected AssetGroupModel $groupModel;
    protected JobTicketModel $ticketModel;
    protected KeywordRuleModel $keywordRuleModel;

    public function __construct()
    {
        $this->model            = new MaintenanceModel();
        $this->assetModel       = new AssetModel();
        $this->groupModel       = new AssetGroupModel();
        $this->ticketModel      = new JobTicketModel();
        $this->keywordRuleModel = new KeywordRuleModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        $keyword = $this->request->getGet('q')    ?? '';
        $bld     = $this->request->getGet('bld')   ?? '';
        $unit    = $this->request->getGet('unit')  ?? '';
        $perPage = 10;

        // When drilling down from the PM plan (bld/unit set), restrict to current month only
        $filterMonth = ($bld !== '' || $unit !== '') ? (int) date('n') : 0;
        $filterYear  = ($bld !== '' || $unit !== '') ? (int) date('Y') : 0;

        $records = $keyword
            ? $this->model->searchPaginated($keyword, $perPage, $bld, $unit, $filterMonth, $filterYear)
            : $this->model->withAssetPaginated($perPage, $bld, $unit, $filterMonth, $filterYear);

        $pager = $this->model->pager;

        $bldTotal = $this->model->countByBuilding($bld, $unit, $filterMonth, $filterYear);

        $stats = [
            'total'      => $this->model->countAll(),
            'this_month' => $this->model->where('MONTH(maintenance_date)', date('m'))
                                        ->where('YEAR(maintenance_date)', date('Y'))
                                        ->countAllResults(),
            'total_cost' => $this->model->selectSum('cost')->first()['cost'] ?? 0,
            'groups'     => $this->model->select('group_id')->distinct()->where('group_id IS NOT NULL', null, false)->countAllResults(),
        ];

        $db           = \Config\Database::connect();
        $currentMonth = (int) date('n');
        $currentYear  = (int) date('Y');

        // PM Plan schedule for the current month — one row per scheduled asset
        $pmRows = $db->table('pm_plan_items ppi')
            ->select("
                COALESCE(b.name, '—')  AS building_name,
                COALESCE(ou.name, '—') AS unit_name,
                ppi.description,
                ppi.frequency,
                ppi.asset_id,
                a.asset_tag,
                CASE WHEN am.maintenance_id IS NOT NULL THEN 1 ELSE 0 END AS is_done
            ", false)
            ->join('pm_plans pp',          'pp.plan_id  = ppi.plan_id')
            ->join('assets a',             'a.asset_id  = ppi.asset_id',          'left')
            ->join('asset_groups ag',      'ag.group_id = a.group_id',             'left')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id',  'left')
            ->join('buildings b',          'b.building_id = ou.building_id',       'left')
            ->join("asset_maintenance am",
                   "am.asset_id = ppi.asset_id
                    AND MONTH(am.maintenance_date) = {$currentMonth}
                    AND YEAR(am.maintenance_date)  = {$currentYear}",
                   'left')
            ->where('pp.plan_year', $currentYear)
            ->where("JSON_CONTAINS(ppi.schedule_months, CAST({$currentMonth} AS CHAR))", null, false)
            ->orderBy('b.name')
            ->orderBy('ou.name')
            ->orderBy('ppi.sort_order')
            ->get()->getResultArray();

        // Build nested: building → { scheduled, done }  &  building → unit → { scheduled, done, assets[] }
        $pmBuildingStats = [];
        $pmOuStats       = [];
        foreach ($pmRows as $row) {
            $bld  = $row['building_name'];
            $unit = $row['unit_name'];
            if (!isset($pmBuildingStats[$bld])) {
                $pmBuildingStats[$bld] = ['scheduled' => 0, 'done' => 0];
            }
            $pmBuildingStats[$bld]['scheduled']++;
            if ($row['is_done']) $pmBuildingStats[$bld]['done']++;

            if (!isset($pmOuStats[$bld][$unit])) {
                $pmOuStats[$bld][$unit] = ['scheduled' => 0, 'done' => 0, 'assets' => []];
            }
            $pmOuStats[$bld][$unit]['scheduled']++;
            if ($row['is_done']) $pmOuStats[$bld][$unit]['done']++;
            $pmOuStats[$bld][$unit]['assets'][] = [
                'description' => $row['description'],
                'asset_tag'   => $row['asset_tag'] ?? '—',
                'frequency'   => $row['frequency'],
                'is_done'     => (bool) $row['is_done'],
            ];
        }

        // Org-unit record counts (for showing in maintenance log filter)
        $ouRows = $db->table('asset_maintenance am')
            ->select("COALESCE(b.name, '—') AS building_name, COALESCE(ou.name, '—') AS unit_name, COUNT(*) AS cnt")
            ->join('asset_groups ag',      'ag.group_id   = am.group_id',          'left')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id',  'left')
            ->join('buildings b',          'b.building_id = ou.building_id',       'left')
            ->groupBy('building_name, unit_name')
            ->orderBy('cnt', 'DESC')
            ->get()->getResultArray();
        $orgUnitStats = [];
        foreach ($ouRows as $row) {
            $orgUnitStats[$row['building_name']][$row['unit_name']] = (int) $row['cnt'];
        }

        return view('maintenance/index', [
            'records'         => $records,
            'keyword'         => $keyword,
            'stats'           => $stats,
            'bldTotal'        => $bldTotal,
            'pager'           => $pager,
            'perPage'         => $perPage,
            'pmBuildingStats' => $pmBuildingStats,
            'pmOuStats'       => $pmOuStats,
            'orgUnitStats'    => $orgUnitStats,
            'currentMonth'    => date('F'),
            'currentYear'     => $currentYear,
        ]);
    }

    public function create(): string
    {
        $db     = \Config\Database::connect();
        $groups = $db->table('asset_groups ag')
            ->select('ag.*, ou.name AS unit_name, ou.unit_id AS unit_id, b.name AS building_name, b.building_id AS building_id')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
            ->join('buildings b', 'b.building_id = ou.building_id', 'left')
            ->orderBy('ag.group_name', 'ASC')
            ->get()->getResultArray();

        $allAssets = $this->assetModel->findAll();
        $groupedAssets = [];
        foreach ($allAssets as $a) {
            if ($a['group_id']) {
                $groupedAssets[$a['group_id']][] = $a;
            }
        }

        return view('maintenance/create', [
            'validation'       => \Config\Services::validation(),
            'groups'           => $groups,
            'groupId'          => $this->request->getGet('group_id'),
            'groupedAssets'    => $groupedAssets,
            'jobTickets'       => $this->ticketModel->forSelect(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
        ]);
    }

    public function store()
    {
        $rules = [
            'group_id'            => 'required|integer',
            'maintenance_date'    => 'required|valid_date',
            'frequency'           => 'required',
            'conducted_by'        => 'required',
            'conducted_date'      => 'required|valid_date',
            'verified_by'         => 'required',
            'verified_date'       => 'required|valid_date',
            'remarks'             => 'required',
            'corrective_action'   => 'required',
            'corrective_date'     => 'required|valid_date',
            'responsible_person'  => 'required',
            'responsible_date'    => 'required|valid_date',
            'responsible_remarks' => 'required',
        ];

        if (! $this->validate($rules)) {
            $db     = \Config\Database::connect();
            $groups = $db->table('asset_groups ag')
                ->select('ag.*, ou.name AS unit_name, ou.unit_id AS unit_id, b.name AS building_name, b.building_id AS building_id')
                ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
                ->join('buildings b', 'b.building_id = ou.building_id', 'left')
                ->orderBy('ag.group_name', 'ASC')
                ->get()->getResultArray();
            $allAssets = $this->assetModel->findAll();
            $groupedAssets = [];
            foreach ($allAssets as $a) {
                if ($a['group_id']) $groupedAssets[$a['group_id']][] = $a;
            }
            return view('maintenance/create', [
                'validation'       => $this->validator,
                'errors'           => $this->validator->getErrors(),
                'groups'           => $groups,
                'groupId'          => $this->request->getPost('group_id'),
                'groupedAssets'    => $groupedAssets,
                'jobTickets'       => $this->ticketModel->forSelect(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            ]);
        }

        $assetIds = $this->request->getPost('asset_ids') ?? [];
        $commonData = [
            'group_id'            => $this->request->getPost('group_id') ?: null,
            'job_ticket_id'       => $this->request->getPost('job_ticket_id') ?: null,
            'equipment_type'      => $this->request->getPost('equipment_type') ?: null,
            'frequency'           => $this->request->getPost('frequency') ?: null,
            'activities'          => $this->request->getPost('activities') ?: null,
            'conducted_by'        => $this->request->getPost('conducted_by') ?: null,
            'conducted_date'      => $this->request->getPost('conducted_date') ?: null,
            'verified_by'         => $this->request->getPost('verified_by') ?: null,
            'verified_date'       => $this->request->getPost('verified_date') ?: null,
            'remarks'             => $this->request->getPost('remarks') ?: null,
            'issue_description'   => $this->request->getPost('issue_description'),
            'action_taken'        => $this->request->getPost('action_taken'),
            'parts_replaced'      => $this->request->getPost('parts_replaced'),
            'maintenance_date'    => $this->request->getPost('maintenance_date'),
            'technician_id'       => $this->request->getPost('technician_id') ?: null,
            'cost'                => $this->request->getPost('cost') ?: 0,
            'corrective_action'   => $this->request->getPost('corrective_action') ?: null,
            'corrective_date'     => $this->request->getPost('corrective_date') ?: null,
            'responsible_person'  => $this->request->getPost('responsible_person') ?: null,
            'responsible_date'    => $this->request->getPost('responsible_date') ?: null,
            'responsible_remarks' => $this->request->getPost('responsible_remarks') ?: null,
        ];

        if (!empty($assetIds)) {
            foreach ($assetIds as $aid) {
                $this->model->insert(array_merge($commonData, ['asset_id' => $aid]));
            }
        } else {
            $this->model->insert(array_merge($commonData, ['asset_id' => null]));
        }

        $count = max(count($assetIds), 1);
        return redirect()->to(site_url($this->resolveRoutePrefix() . '/maintenance'))->with('success', $count . ' maintenance record(s) added successfully.');
    }

    public function show(int $id): string
    {
        $record = $this->model->findWithAsset($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db       = \Config\Database::connect();
        $siblings = $db->table('asset_maintenance am')
            ->select('am.asset_id, a.asset_tag, a.brand_model, a.serial_number, a.category,
                      ou.name AS unit_name, b.name AS building_name')
            ->join('assets a', 'a.asset_id = am.asset_id', 'left')
            ->join('asset_groups ag', 'ag.group_id = am.group_id', 'left')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
            ->join('buildings b', 'b.building_id = ou.building_id', 'left')
            ->where('am.group_id', $record['group_id'])
            ->where('am.maintenance_date', $record['maintenance_date'])
            ->where('am.asset_id IS NOT NULL')
            ->orderBy('b.name, ou.name, a.asset_tag')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($siblings as $row) {
            $loc = trim(($row['building_name'] ?? '') . ($row['unit_name'] ? ' – ' . $row['unit_name'] : ''));
            if (!$loc) $loc = $record['group_name'] ?? 'Location';
            if (!isset($grouped[$loc])) $grouped[$loc] = [];
            $grouped[$loc][] = $row;
        }
        $assetGroups = [];
        foreach ($grouped as $label => $assets) {
            $assetGroups[] = ['label' => $label, 'assets' => $assets];
        }
        if (empty($assetGroups) && !empty($record['group_id'])) {
            $assetGroups[] = ['label' => $record['group_name'] ?? 'Group', 'assets' => []];
        }

        return view('maintenance/show', ['record' => $record, 'assetGroups' => $assetGroups]);
    }

    public function print(int $id): string
    {
        $record = $this->model->findWithAsset($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db       = \Config\Database::connect();
        $siblings = $db->table('asset_maintenance am')
            ->select('am.asset_id, a.asset_tag, a.brand_model, a.serial_number, a.category,
                      ou.name AS unit_name, b.name AS building_name')
            ->join('assets a', 'a.asset_id = am.asset_id', 'left')
            ->join('asset_groups ag', 'ag.group_id = am.group_id', 'left')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
            ->join('buildings b', 'b.building_id = ou.building_id', 'left')
            ->where('am.group_id', $record['group_id'])
            ->where('am.maintenance_date', $record['maintenance_date'])
            ->where('am.asset_id IS NOT NULL')
            ->orderBy('b.name, ou.name, a.asset_tag')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($siblings as $row) {
            $loc = trim(($row['building_name'] ?? '') . ($row['unit_name'] ? ' – ' . $row['unit_name'] : ''));
            if (!$loc) $loc = $record['group_name'] ?? 'Location';
            if (!isset($grouped[$loc])) $grouped[$loc] = [];
            $grouped[$loc][] = $row;
        }

        $assetGroups = [];
        foreach ($grouped as $label => $assets) {
            $assetGroups[] = ['label' => $label, 'assets' => $assets];
        }

        if (empty($assetGroups) && !empty($record['group_id'])) {
            $assetGroups[] = [
                'label'  => $record['group_name'] ?? 'Group',
                'assets' => [],
            ];
        }

        return view('maintenance/print', [
            'record'      => $record,
            'assetGroups' => $assetGroups,
        ]);
    }

    public function printChecklist(int $id): string
    {
        $record = $this->model->findWithAsset($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Load all sibling records (same group + maintenance date = one session)
        $db       = \Config\Database::connect();
        $siblings = $db->table('asset_maintenance am')
            ->select('am.asset_id, a.asset_tag, a.brand_model, a.serial_number, a.category,
                      ou.name AS unit_name, b.name AS building_name')
            ->join('assets a', 'a.asset_id = am.asset_id', 'left')
            ->join('asset_groups ag', 'ag.group_id = am.group_id', 'left')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
            ->join('buildings b', 'b.building_id = ou.building_id', 'left')
            ->where('am.group_id', $record['group_id'])
            ->where('am.maintenance_date', $record['maintenance_date'])
            ->where('am.asset_id IS NOT NULL')
            ->orderBy('b.name, ou.name, a.asset_tag')
            ->get()->getResultArray();

        // Group assets by "Building – Org Unit" label
        $grouped = [];
        foreach ($siblings as $row) {
            $loc = trim(($row['building_name'] ?? '') . ($row['unit_name'] ? ' – ' . $row['unit_name'] : ''));
            if (!$loc) $loc = $record['group_name'] ?? 'Location';
            if (!isset($grouped[$loc])) $grouped[$loc] = [];
            $grouped[$loc][] = $row;
        }

        $assetGroups = [];
        foreach ($grouped as $label => $assets) {
            $assetGroups[] = ['label' => $label, 'assets' => $assets];
        }

        // If no linked assets, still show the group name as a single column header with no rows
        if (empty($assetGroups) && !empty($record['group_id'])) {
            $assetGroups[] = [
                'label'  => $record['group_name'] ?? 'Group',
                'assets' => [],
            ];
        }

        return view('maintenance/print_checklist', [
            'record'      => $record,
            'assetGroups' => $assetGroups,
        ]);
    }

    public function edit(int $id): string
    {
        $record = $this->model->find($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db     = \Config\Database::connect();
        $groups = $db->table('asset_groups ag')
            ->select('ag.*, ou.name AS unit_name, ou.unit_id AS unit_id, b.name AS building_name, b.building_id AS building_id')
            ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
            ->join('buildings b', 'b.building_id = ou.building_id', 'left')
            ->orderBy('ag.group_name', 'ASC')
            ->get()->getResultArray();

        $allAssets = $this->assetModel->findAll();
        $groupedAssets = [];
        foreach ($allAssets as $a) {
            if ($a['group_id']) $groupedAssets[$a['group_id']][] = $a;
        }

        return view('maintenance/edit', [
            'record'           => $record,
            'groups'           => $groups,
            'groupedAssets'    => $groupedAssets,
            'validation'       => \Config\Services::validation(),
            'jobTickets'       => $this->ticketModel->forSelect(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
        ]);
    }

    public function update(int $id)
    {
        $record = $this->model->find($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'group_id'         => 'required|integer',
            'maintenance_date' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            $db     = \Config\Database::connect();
            $groups = $db->table('asset_groups ag')
                ->select('ag.*, ou.name AS unit_name, ou.unit_id AS unit_id, b.name AS building_name, b.building_id AS building_id')
                ->join('organizational_units ou', 'ou.unit_id = ag.assigned_unit_id', 'left')
                ->join('buildings b', 'b.building_id = ou.building_id', 'left')
                ->orderBy('ag.group_name', 'ASC')
                ->get()->getResultArray();
            $allAssets = $this->assetModel->findAll();
            $groupedAssets = [];
            foreach ($allAssets as $a) {
                if ($a['group_id']) $groupedAssets[$a['group_id']][] = $a;
            }
            return view('maintenance/edit', [
                'record'           => $record,
                'groups'           => $groups,
                'groupedAssets'    => $groupedAssets,
                'validation'       => $this->validator,
                'jobTickets'       => $this->ticketModel->forSelect(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            ]);
        }

        $this->model->update($id, [
            'group_id'            => $this->request->getPost('group_id') ?: null,
            'asset_id'            => $this->request->getPost('asset_id') ?: null,
            'job_ticket_id'       => $this->request->getPost('job_ticket_id') ?: null,
            'equipment_type'      => $this->request->getPost('equipment_type') ?: null,
            'frequency'           => $this->request->getPost('frequency') ?: null,
            'activities'          => $this->request->getPost('activities') ?: null,
            'conducted_by'        => $this->request->getPost('conducted_by') ?: null,
            'conducted_date'      => $this->request->getPost('conducted_date') ?: null,
            'verified_by'         => $this->request->getPost('verified_by') ?: null,
            'verified_date'       => $this->request->getPost('verified_date') ?: null,
            'remarks'             => $this->request->getPost('remarks') ?: null,
            'maintenance_date'    => $this->request->getPost('maintenance_date'),
            'cost'                => $this->request->getPost('cost') ?: 0,
            'corrective_action'   => $this->request->getPost('corrective_action') ?: null,
            'corrective_date'     => $this->request->getPost('corrective_date') ?: null,
            'responsible_person'  => $this->request->getPost('responsible_person') ?: null,
            'responsible_date'    => $this->request->getPost('responsible_date') ?: null,
            'responsible_remarks' => $this->request->getPost('responsible_remarks') ?: null,
        ]);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/maintenance'))->with('success', 'Maintenance record updated.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        $bld  = $this->request->getGet('bld')  ?? '';
        $unit = $this->request->getGet('unit') ?? '';
        $back = site_url($this->resolveRoutePrefix() . '/maintenance') . '?show_log=1';
        if ($bld  !== '') $back .= '&bld='  . urlencode($bld);
        if ($unit !== '') $back .= '&unit=' . urlencode($unit);
        return redirect()->to($back)->with('success', 'Maintenance record deleted.');
    }

    private function resolveRoutePrefix(): string
    {
        $path = trim((string) $this->request->getUri()->getPath(), '/');

        if (str_starts_with($path, 'admin/')) {
            return 'admin';
        }

        if (str_starts_with($path, 'super-admin/')) {
            return 'super-admin';
        }

        $sessionUser = session()->get('user');
        if (isset($sessionUser['role_id']) && (int) $sessionUser['role_id'] === 2) {
            return 'admin';
        }

        return 'super-admin';
    }
}
