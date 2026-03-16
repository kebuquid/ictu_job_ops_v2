<?php

namespace App\Controllers;

use App\Models\PreventiveMaintenancePlanModel;
use App\Models\PreventiveMaintenancePlanItemModel;
use App\Models\AssetModel;
use App\Models\AssetGroupModel;

class PreventiveMaintenancePlan extends BaseController
{
    protected PreventiveMaintenancePlanModel     $planModel;
    protected PreventiveMaintenancePlanItemModel $itemModel;
    protected AssetModel                         $assetModel;
    protected AssetGroupModel                    $groupModel;

    public function __construct()
    {
        $this->planModel  = new PreventiveMaintenancePlanModel();
        $this->itemModel  = new PreventiveMaintenancePlanItemModel();
        $this->assetModel = new AssetModel();
        $this->groupModel = new AssetGroupModel();
        helper(['form', 'url']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX – all plans
    // ──────────────────────────────────────────────────────────────────────────
    public function index(): string
    {
        $perPage = 10;
        $keyword = $this->request->getGet('q');

        if ($keyword) {
            $plans = $this->planModel
                ->like('title', $keyword)
                ->orLike('prepared_by', $keyword)
                ->orLike('plan_year', $keyword)
                ->paginate($perPage, 'default');
        } else {
            $plans = $this->planModel->listPaginated($perPage);
        }

        return view('pm_plans/index', [
            'plans'   => $plans,
            'pager'   => $this->planModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total'   => $this->planModel->countAll(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE form
    // ──────────────────────────────────────────────────────────────────────────
    public function create(): string
    {
        $groups    = $this->groupModel->orderBy('group_name')->findAll();
        $allAssets = $this->assetModel->orderBy('brand_model')->findAll();

        return view('pm_plans/create', [
            'validation'      => \Config\Services::validation(),
            'groups'          => $groups,
            'allAssets'       => $allAssets,
            'scheduledByYear' => $this->_scheduledByYear(),
            'curYear'         => (int) date('Y'),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE – save new plan
    // ──────────────────────────────────────────────────────────────────────────
    public function store()
    {
        $rules = [
            'plan_year'   => 'required|integer|exact_length[4]',
            'title'       => 'required|min_length[3]',
            'prepared_by' => 'required',
            'reviewed_by' => 'required',
            'approved_by' => 'required',
        ];

        if (! $this->validate($rules)) {
            $groups    = $this->groupModel->orderBy('group_name')->findAll();
            $allAssets = $this->assetModel->orderBy('brand_model')->findAll();
            return view('pm_plans/create', [
                'validation'      => $this->validator,
                'groups'          => $groups,
                'allAssets'       => $allAssets,
                'scheduledByYear' => $this->_scheduledByYear(),
                'curYear'         => (int) date('Y'),
            ]);
        }

        // Save header
        $planId = $this->planModel->insert([
            'plan_year'      => $this->request->getPost('plan_year'),
            'title'          => $this->request->getPost('title'),
            'department'     => $this->request->getPost('department') ?: null,
            'document_code'  => $this->request->getPost('document_code') ?: 'CSPC-F-ICTU-13',
            'prepared_by'    => $this->request->getPost('prepared_by'),
            'prepared_title' => $this->request->getPost('prepared_title') ?: null,
            'reviewed_by'    => $this->request->getPost('reviewed_by'),
            'reviewed_title' => $this->request->getPost('reviewed_title') ?: null,
            'approved_by'    => $this->request->getPost('approved_by'),
            'approved_title' => $this->request->getPost('approved_title') ?: null,
        ]);

        // Save items (rows)
        $descriptions    = $this->request->getPost('desc')        ?? [];
        $frequencies     = $this->request->getPost('freq')        ?? [];
        $assetIds        = $this->request->getPost('item_asset_id') ?? [];
        $scheduleMonths  = $this->request->getPost('months')      ?? [];

        foreach ($descriptions as $i => $desc) {
            $desc = trim($desc);
            if ($desc === '') {
                continue;
            }

            $freq    = $frequencies[$i]    ?? 'quarterly';
            $months  = $scheduleMonths[$i] ?? [];

            // If no months selected, auto-compute from frequency
            if (empty($months)) {
                $months = PreventiveMaintenancePlanItemModel::defaultMonths($freq);
            }

            $this->itemModel->insert([
                'plan_id'         => $planId,
                'asset_id'        => ! empty($assetIds[$i]) ? (int) $assetIds[$i] : null,
                'description'     => $desc,
                'frequency'       => $freq,
                'schedule_months' => json_encode(array_map('intval', (array) $months)),
                'sort_order'      => $i,
            ]);
        }

        return redirect()->to(site_url('super-admin/pm-plans'))
                         ->with('success', 'Preventive Maintenance Plan created for year ' . $this->request->getPost('plan_year') . '.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW – printable plan
    // ──────────────────────────────────────────────────────────────────────────
    public function show(int $id): string
    {
        $plan = $this->planModel->findWithItems($id);
        if (! $plan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pm_plans/show', ['plan' => $plan]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT form
    // ──────────────────────────────────────────────────────────────────────────
    public function edit(int $id): string
    {
        $plan = $this->planModel->findWithItems($id);
        if (! $plan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $groups    = $this->groupModel->orderBy('group_name')->findAll();
        $allAssets = $this->assetModel->orderBy('brand_model')->findAll();

        return view('pm_plans/edit', [
            'plan'       => $plan,
            'validation' => \Config\Services::validation(),
            'groups'     => $groups,
            'allAssets'  => $allAssets,
            'curYear'    => (int) date('Y'),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────
    public function update(int $id)
    {
        $plan = $this->planModel->find($id);
        if (! $plan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'plan_year'   => 'required|integer|exact_length[4]',
            'title'       => 'required|min_length[3]',
            'prepared_by' => 'required',
            'reviewed_by' => 'required',
            'approved_by' => 'required',
        ];

        if (! $this->validate($rules)) {
            $groups    = $this->groupModel->orderBy('group_name')->findAll();
            $allAssets = $this->assetModel->orderBy('brand_model')->findAll();
            $planFull  = $this->planModel->findWithItems($id);
            return view('pm_plans/edit', [
                'plan'       => $planFull,
                'validation' => $this->validator,
                'groups'     => $groups,
                'allAssets'  => $allAssets,
                'curYear'    => (int) date('Y'),
            ]);
        }

        $this->planModel->update($id, [
            'plan_year'      => $this->request->getPost('plan_year'),
            'title'          => $this->request->getPost('title'),
            'department'     => $this->request->getPost('department') ?: null,
            'document_code'  => $this->request->getPost('document_code') ?: 'CSPC-F-ICTU-13',
            'prepared_by'    => $this->request->getPost('prepared_by'),
            'prepared_title' => $this->request->getPost('prepared_title') ?: null,
            'reviewed_by'    => $this->request->getPost('reviewed_by'),
            'reviewed_title' => $this->request->getPost('reviewed_title') ?: null,
            'approved_by'    => $this->request->getPost('approved_by'),
            'approved_title' => $this->request->getPost('approved_title') ?: null,
        ]);

        // Delete old items and re-insert
        $db = \Config\Database::connect();
        $db->table('pm_plan_items')->where('plan_id', $id)->delete();

        $descriptions   = $this->request->getPost('desc')          ?? [];
        $frequencies    = $this->request->getPost('freq')          ?? [];
        $assetIds       = $this->request->getPost('item_asset_id') ?? [];
        $scheduleMonths = $this->request->getPost('months')        ?? [];

        foreach ($descriptions as $i => $desc) {
            $desc = trim($desc);
            if ($desc === '') {
                continue;
            }
            $freq   = $frequencies[$i]    ?? 'quarterly';
            $months = $scheduleMonths[$i] ?? [];
            if (empty($months)) {
                $months = PreventiveMaintenancePlanItemModel::defaultMonths($freq);
            }
            $this->itemModel->insert([
                'plan_id'         => $id,
                'asset_id'        => ! empty($assetIds[$i]) ? (int) $assetIds[$i] : null,
                'description'     => $desc,
                'frequency'       => $freq,
                'schedule_months' => json_encode(array_map('intval', (array) $months)),
                'sort_order'      => $i,
            ]);
        }

        return redirect()->to(site_url('super-admin/pm-plans'))
                         ->with('success', 'Plan updated successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Returns a map of plan_year => [asset_id, ...] for all existing plan items.
     * Used by create/store views to grey-out already-scheduled assets.
     */
    private function _scheduledByYear(): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->table('pm_plan_items')
                   ->select('pm_plans.plan_year, pm_plan_items.asset_id')
                   ->join('pm_plans', 'pm_plans.plan_id = pm_plan_items.plan_id')
                   ->where('pm_plan_items.asset_id IS NOT NULL')
                   ->get()
                   ->getResultArray();
        $map = [];
        foreach ($rows as $row) {
            $map[(int)$row['plan_year']][] = (int)$row['asset_id'];
        }
        return $map;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────────────────────────────────────
    public function delete(int $id)
    {
        $plan = $this->planModel->find($id);
        if ($plan) {
            $db = \Config\Database::connect();
            $db->table('pm_plan_items')->where('plan_id', $id)->delete();
            $this->planModel->delete($id);
        }

        return redirect()->to(site_url('super-admin/pm-plans'))
                         ->with('success', 'Plan deleted.');
    }
}
