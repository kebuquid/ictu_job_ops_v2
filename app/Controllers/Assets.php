<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\AssetGroupModel;
use App\Models\BuildingModel;
use App\Models\OrganizationalUnitModel;
use App\Models\SectionModel;
use App\Models\UserModel;
use App\Models\KeywordRuleModel;

class Assets extends BaseController
{
    protected AssetModel $model;
    protected AssetGroupModel $groupModel;
    protected BuildingModel $buildingModel;
    protected OrganizationalUnitModel $unitModel;
    protected SectionModel $sectionModel;
    protected UserModel $userModel;
    protected KeywordRuleModel $keywordRuleModel;

    public function __construct()
    {
        $this->model             = new AssetModel();
        $this->groupModel        = new AssetGroupModel();
        $this->buildingModel     = new BuildingModel();
        $this->unitModel         = new OrganizationalUnitModel();
        $this->sectionModel      = new SectionModel();
        $this->userModel         = new UserModel();
        $this->keywordRuleModel  = new KeywordRuleModel();
        helper(['form', 'url']);
    }

    // LIST
    public function index(): string
    {
        $perPage      = 15;
        $keyword      = $this->request->getGet('q');
        $filterStatus = $this->request->getGet('status');

        $query = $this->model->orderBy('created_at', 'DESC');

        if ($keyword) {
            $query->groupStart()
                  ->like('asset_tag', $keyword)
                  ->orLike('property_no', $keyword)
                  ->orLike('brand_model', $keyword)
                  ->orLike('serial_number', $keyword)
                  ->orLike('category', $keyword)
                  ->orLike('status', $keyword)
                  ->groupEnd();
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $assets = $query->paginate($perPage, 'assets');
        $pager  = $this->model->pager;

        // Dashboard stats
        $stats = [
            'total'        => $this->model->countAll(),
            'active'       => $this->model->where('status', 'Active')->countAllResults(),
            'under_repair' => $this->model->where('status', 'Under Repair')->countAllResults(),
            'disposed'     => $this->model->where('status', 'Disposed')->countAllResults(),
            'inactive'     => $this->model->where('status', 'Inactive')->countAllResults(),
        ];

        return view('assets/index', [
            'assets'       => $assets,
            'pager'        => $pager,
            'keyword'      => $keyword,
            'filterStatus' => $filterStatus,
            'stats'        => $stats,
        ]);
    }

    // CREATE FORM
    public function create(): string
    {
        return view('assets/create', [
            'validation'       => \Config\Services::validation(),
            'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
            'units'            => $this->unitModel->orderBy('name')->findAll(),
            'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
            'sections'         => $this->sectionModel->orderBy('name')->findAll(),
            'users'            => model('App\Models\UserModel')->orderBy('name')->findAll(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
        ]);
    }

    // STORE
    public function store()
    {
        $rules = [
            'asset_tag'         => 'required|max_length[100]|is_unique[assets.asset_tag]',
            'property_no'       => 'permit_empty|max_length[100]',
            'brand_model'       => 'permit_empty|max_length[150]',
            'serial_number'     => 'permit_empty|max_length[100]',
            'category'          => 'required|max_length[100]',
            'date_acquired'     => 'permit_empty|valid_date',
            'warranty_end'      => 'permit_empty|valid_date',
            'acquisition_cost'  => 'permit_empty|decimal',
            'depreciation_cost' => 'permit_empty|decimal',
            'lifecycle'         => 'permit_empty',
            'assigned_to'       => 'permit_empty|integer',
            'section_id'        => 'permit_empty|integer',
            'assigned_unit_id'  => 'permit_empty|integer',
            'group_id'          => 'permit_empty|integer',
            'status'            => 'required|in_list[Active,Inactive,Under Repair,Disposed]',
        ];

        if (! $this->validate($rules)) {
            return view('assets/create', [
                'validation'       => $this->validator,
                'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
                'units'            => $this->unitModel->orderBy('name')->findAll(),
                'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
                'sections'         => $this->sectionModel->orderBy('name')->findAll(),
                'users'            => model('App\Models\UserModel')->orderBy('name')->findAll(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            ]);
        }

        $imageName  = null;
        $imageFile  = $this->request->getFile('asset_image');
        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'assets';

        // Ensure directory exists
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($imageFile) {
            log_message('debug', '[Assets::store] file name={0} error={1} size={2} valid={3} moved={4}', [
                $imageFile->getClientName(),
                $imageFile->getError(),
                $imageFile->getSize(),
                $imageFile->isValid() ? 'yes' : 'no',
                $imageFile->hasMoved() ? 'yes' : 'no',
            ]);
        }

        if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK && ! $imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
        }

        $this->model->insert([
            'asset_tag'          => $this->request->getPost('asset_tag'),
            'property_no'        => $this->request->getPost('property_no'),
            'brand_model'        => $this->request->getPost('brand_model'),
            'serial_number'      => $this->request->getPost('serial_number'),
            'category'           => $this->request->getPost('category'),
            'operating_system'   => $this->request->getPost('operating_system') ?: null,
            'os_license_key'     => $this->request->getPost('os_license_key') ?: null,
            'os_license_type'    => $this->request->getPost('os_license_type') ?: null,
            'os_license_expiry'  => $this->request->getPost('os_license_expiry') ?: null,
            'os_last_updated'    => $this->request->getPost('os_last_updated') ?: null,
            'os_is_updated'      => (int) ($this->request->getPost('os_is_updated') ?? 0),
            'software_installed' => $this->request->getPost('software_installed') ?: null,
            'software_license'   => $this->request->getPost('software_license') ?: null,
            'software_list'      => (function() {
                $list = $this->request->getPost('software_list');
                if (!is_array($list)) return null;
                $clean = array_values(array_filter($list, fn($s) => !empty(trim($s['name'] ?? ''))));
                return $clean ? json_encode($clean) : null;
            })(),
            'section_id'         => $this->request->getPost('section_id') ?: null,
            'assigned_to'        => $this->request->getPost('assigned_to') ?: null,
            'assigned_unit_id'   => $this->request->getPost('assigned_unit_id') ?: null,
            'date_acquired'      => $this->request->getPost('date_acquired') ?: null,
            'acquisition_cost'   => $this->request->getPost('acquisition_cost') ?: null,
            'depreciation_cost'  => $this->request->getPost('depreciation_cost') ?: null,
            'warranty_end'       => $this->request->getPost('warranty_end') ?: null,
            'status'             => $this->request->getPost('status') ?: 'Active',
            'lifecycle'          => $this->request->getPost('lifecycle'),
            'supplier'           => $this->request->getPost('supplier') ?: null,
            'po_number'          => $this->request->getPost('po_number') ?: null,
            'invoice_number'     => $this->request->getPost('invoice_number') ?: null,
            'procurement_mode'   => $this->request->getPost('procurement_mode') ?: null,
            'fund_source'        => $this->request->getPost('fund_source') ?: null,
            'group_id'           => $this->request->getPost('group_id') ?: null,
            'asset_image'        => $imageName,
        ]);

        // Sync group quantity and costs if a group was selected
        $groupId = (int) ($this->request->getPost('group_id') ?: 0);
        if ($groupId) {
            $total = $this->model->where('group_id', $groupId)->countAllResults();
            $this->groupModel->update($groupId, ['quantity' => $total]);
            $this->syncGroupCosts($groupId);
        }

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/assets'))->with('success', 'Asset added successfully.');
    }

    // SHOW
    public function show(int $id): string
    {
        $asset = $this->model->find($id);
        if (! $asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Asset #{$id} not found.");
        }

        // Resolve building and unit names
        $unit         = $asset['assigned_unit_id'] ? $this->unitModel->find($asset['assigned_unit_id']) : null;
        $building     = ($unit && $unit['building_id']) ? $this->buildingModel->find($unit['building_id']) : null;
        $section      = $asset['section_id'] ? $this->sectionModel->find($asset['section_id']) : null;
        $assignedUser = $asset['assigned_to'] ? $this->userModel->find($asset['assigned_to']) : null;

        return view('assets/show', [
            'asset'          => $asset,
            'unitName'       => $unit['name']         ?? null,
            'buildingName'   => $building['name']     ?? null,
            'sectionName'    => $section ? ($section['acronym'] ?? $section['name'] ?? null) : null,
            'assignedToName' => $assignedUser['name'] ?? null,
        ]);
    }

    // EDIT FORM
    public function edit(int $id): string
    {
        $asset = $this->model->find($id);
        if (! $asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Asset #{$id} not found.");
        }

        return view('assets/edit', [
            'asset'            => $asset,
            'validation'       => \Config\Services::validation(),
            'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
            'units'            => $this->unitModel->orderBy('name')->findAll(),
            'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
            'sections'         => $this->sectionModel->orderBy('name')->findAll(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
        ]);
    }

    // UPDATE
    public function update(int $id)
    {
        $asset = $this->model->find($id);
        if (! $asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Asset #{$id} not found.");
        }

        $rules = [
            'asset_tag' => "required|max_length[100]|is_unique[assets.asset_tag,asset_id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return view('assets/edit', [
                'asset'            => $asset,
                'validation'       => $this->validator,
                'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
                'units'            => $this->unitModel->orderBy('name')->findAll(),
                'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
                'sections'         => $this->sectionModel->orderBy('name')->findAll(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            ]);
        }

        $updateData = [
            'asset_tag'          => $this->request->getPost('asset_tag'),
            'property_no'        => $this->request->getPost('property_no'),
            'brand_model'        => $this->request->getPost('brand_model'),
            'serial_number'      => $this->request->getPost('serial_number'),
            'category'           => $this->request->getPost('category'),
            'operating_system'   => $this->request->getPost('operating_system') ?: null,
            'os_license_key'     => $this->request->getPost('os_license_key') ?: null,
            'os_license_type'    => $this->request->getPost('os_license_type') ?: null,
            'os_license_expiry'  => $this->request->getPost('os_license_expiry') ?: null,
            'os_last_updated'    => $this->request->getPost('os_last_updated') ?: null,
            'os_is_updated'      => (int) ($this->request->getPost('os_is_updated') ?? 0),
            'software_installed' => $this->request->getPost('software_installed') ?: null,
            'software_license'   => $this->request->getPost('software_license') ?: null,
            'software_list'      => (function() {
                $list = $this->request->getPost('software_list');
                if (!is_array($list)) return null;
                $clean = array_values(array_filter($list, fn($s) => !empty(trim($s['name'] ?? ''))));
                return $clean ? json_encode($clean) : null;
            })(),
            'section_id'         => $this->request->getPost('section_id') ?: null,
            'assigned_to'        => $this->request->getPost('assigned_to') ?: null,
            'assigned_unit_id'   => $this->request->getPost('assigned_unit_id') ?: null,
            'date_acquired'      => $this->request->getPost('date_acquired') ?: null,
            'acquisition_cost'   => $this->request->getPost('acquisition_cost') ?: null,
            'depreciation_cost'  => $this->request->getPost('depreciation_cost') ?: null,
            'warranty_end'       => $this->request->getPost('warranty_end') ?: null,
            'status'             => $this->request->getPost('status') ?: 'Active',
            'lifecycle'          => $this->request->getPost('lifecycle'),
            'supplier'           => $this->request->getPost('supplier') ?: null,
            'po_number'          => $this->request->getPost('po_number') ?: null,
            'invoice_number'     => $this->request->getPost('invoice_number') ?: null,
            'procurement_mode'   => $this->request->getPost('procurement_mode') ?: null,
            'fund_source'        => $this->request->getPost('fund_source') ?: null,
            'group_id'           => $this->request->getPost('group_id') ?: null,
        ];

        // Handle image upload
        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'assets';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $imageFile = $this->request->getFile('asset_image');
        log_message('debug', '[Assets::update] file={0} error={1} valid={2}', [
            $imageFile ? $imageFile->getClientName() : 'none',
            $imageFile ? $imageFile->getError() : 'n/a',
            ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK) ? 'yes' : 'no',
        ]);
        if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK && ! $imageFile->hasMoved()) {
            // Delete old image if exists
            if (! empty($asset['asset_image'])) {
                $oldPath = $uploadPath . DIRECTORY_SEPARATOR . $asset['asset_image'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
            $updateData['asset_image'] = $imageName;
        } else {
            // No new file — keep the existing image from the hidden input
            // but only if the file actually exists on disk
            $existing = $this->request->getPost('existing_asset_image');
            if ($existing) {
                $existPath = $uploadPath . DIRECTORY_SEPARATOR . $existing;
                $updateData['asset_image'] = is_file($existPath) ? $existing : null;
            }
        }

        $this->model->update($id, $updateData);

        // Sync group quantity and costs for affected groups
        $oldGroupId = (int) ($asset['group_id'] ?? 0);
        $newGroupId = (int) ($this->request->getPost('group_id') ?: 0);
        $groupsToSync = array_unique(array_filter([$oldGroupId, $newGroupId]));
        foreach ($groupsToSync as $gid) {
            $total = $this->model->where('group_id', $gid)->countAllResults();
            $this->groupModel->update($gid, ['quantity' => $total]);
            $this->syncGroupCosts($gid);
        }

        return redirect()->to(site_url($this->resolveRoutePrefix() . "/assets/show/{$id}"))->with('success', 'Asset updated successfully.');
    }

    // DELETE
    public function delete(int $id)
    {
        $asset = $this->model->find($id);
        if (! $asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Asset #{$id} not found.");
        }

        $this->model->delete($id);

        // Sync group if this asset was in one
        $groupId = (int) ($asset['group_id'] ?? 0);
        if ($groupId) {
            $total = $this->model->where('group_id', $groupId)->countAllResults();
            $this->groupModel->update($groupId, ['quantity' => $total]);
            $this->syncGroupCosts($groupId);
        }

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/assets'))->with('success', 'Asset deleted successfully.');
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

    /**
     * Recompute and save average acquisition/depreciation costs for a group
     * based on its currently linked assets (ignoring assets with zero costs).
     */
    private function syncGroupCosts(int $groupId): void
    {
        $assets     = $this->model->where('group_id', $groupId)->findAll();
        $acqValues  = array_filter(array_column($assets, 'acquisition_cost'),  fn($v) => (float)$v > 0);
        $deprValues = array_filter(array_column($assets, 'depreciation_cost'), fn($v) => (float)$v > 0);
        $avgAcq     = count($acqValues)  ? array_sum($acqValues)  / count($acqValues)  : 0;
        $avgDepr    = count($deprValues) ? array_sum($deprValues) / count($deprValues) : 0;
        $this->groupModel->update($groupId, [
            'acquisition_cost'  => round($avgAcq,  2),
            'depreciation_cost' => round($avgDepr, 2),
        ]);
    }
}
