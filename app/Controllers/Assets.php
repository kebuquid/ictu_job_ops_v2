<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\AssetSoftwareModel;
use App\Models\AssetGroupModel;
use App\Models\BuildingModel;
use App\Models\OrganizationalUnitModel;
use App\Models\SectionModel;
use App\Models\UserModel;
use App\Models\KeywordRuleModel;

class Assets extends BaseController
{
    protected AssetModel $model;
    protected AssetSoftwareModel $assetSoftwareModel;
    protected AssetGroupModel $groupModel;
    protected BuildingModel $buildingModel;
    protected OrganizationalUnitModel $unitModel;
    protected SectionModel $sectionModel;
    protected UserModel $userModel;
    protected KeywordRuleModel $keywordRuleModel;

    public function __construct()
    {
        $this->model             = new AssetModel();
        $this->assetSoftwareModel = new AssetSoftwareModel();
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

        $groupIdInput = (int) ($this->request->getPost('group_id') ?: 0);
        $assignedUnitId = null;
        if ($groupIdInput > 0) {
            $group = $this->groupModel->find($groupIdInput);
            if (! $group || empty($group['assigned_unit_id'])) {
                return redirect()->back()->withInput()->with('error', 'Selected asset group has no assigned organizational unit. Please update the group first.');
            }
            $assignedUnitId = (int) $group['assigned_unit_id'];
        }

        $softwares = $this->normalizeSoftwareRows($this->request->getPost('software_list'));

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
            'software_installed' => null,
            'software_license'   => null,
            'section_id'         => $this->request->getPost('section_id') ?: null,
            'assigned_to'        => $this->request->getPost('assigned_to') ?: null,
            'assigned_unit_id'   => $assignedUnitId,
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
            'group_id'           => $groupIdInput ?: null,
            'asset_image'        => $imageName,
        ]);

        $assetId = (int) $this->model->getInsertID();
        $softwareIds = $this->assetSoftwareModel->syncSoftwares($assetId, $softwares);
        $this->model->update($assetId, [
            'software_installed' => $softwareIds !== [] ? implode(',', $softwareIds) : null,
            'software_license'   => $this->buildSoftwareLicenseSummary($softwares),
        ]);

        // Sync group quantity and costs if a group was selected
        $groupId = $groupIdInput;
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
            'softwares'      => $this->assetSoftwareModel->getByAssetId($id),
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
            'softwares'        => $this->assetSoftwareModel->getByAssetId($id),
            'validation'       => \Config\Services::validation(),
            'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
            'units'            => $this->unitModel->orderBy('name')->findAll(),
            'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
            'sections'         => $this->sectionModel->orderBy('name')->findAll(),
            'users'            => $this->userModel->orderBy('name')->findAll(),
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

        $assignedToIdRaw   = trim((string) $this->request->getPost('assigned_to'));
        $assignedToTyped   = trim((string) $this->request->getPost('assigned_to_search'));

        if ($assignedToTyped !== '' && $assignedToIdRaw === '') {
            return redirect()->back()->withInput()->with('error', 'Assigned To is invalid. Please select a valid user from suggestions.');
        }

        if ($assignedToIdRaw !== '' && ! ctype_digit($assignedToIdRaw)) {
            return redirect()->back()->withInput()->with('error', 'Assigned To value is invalid.');
        }

        if ($assignedToIdRaw !== '' && ! $this->userModel->find((int) $assignedToIdRaw)) {
            return redirect()->back()->withInput()->with('error', 'Assigned To user was not found. Please select a valid user.');
        }

        $rules = [
            'asset_tag' => "required|max_length[100]|is_unique[assets.asset_tag,asset_id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return view('assets/edit', [
                'asset'            => $asset,
                'softwares'        => is_array($this->request->getPost('software_list'))
                    ? $this->request->getPost('software_list')
                    : $this->assetSoftwareModel->getByAssetId($id),
                'validation'       => $this->validator,
                'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
                'units'            => $this->unitModel->orderBy('name')->findAll(),
                'groups'           => $this->groupModel->orderBy('group_name')->findAll(),
                'sections'         => $this->sectionModel->orderBy('name')->findAll(),
                'users'            => $this->userModel->orderBy('name')->findAll(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            ]);
        }

        $newGroupId = (int) ($this->request->getPost('group_id') ?: 0);
        $assignedUnitId = null;
        if ($newGroupId > 0) {
            $group = $this->groupModel->find($newGroupId);
            if (! $group || empty($group['assigned_unit_id'])) {
                return redirect()->back()->withInput()->with('error', 'Selected asset group has no assigned organizational unit. Please update the group first.');
            }
            $assignedUnitId = (int) $group['assigned_unit_id'];
        }

        $softwares = $this->normalizeSoftwareRows($this->request->getPost('software_list'));

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
            'software_installed' => null,
            'software_license'   => null,
            'section_id'         => $this->request->getPost('section_id') ?: null,
            'assigned_to'        => $this->request->getPost('assigned_to') ?: null,
            'assigned_unit_id'   => $assignedUnitId,
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
            'group_id'           => $newGroupId ?: null,
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

        $softwareIds = $this->assetSoftwareModel->syncSoftwares($id, $softwares);
        $this->model->update($id, [
            'software_installed' => $softwareIds !== [] ? implode(',', $softwareIds) : null,
            'software_license'   => $this->buildSoftwareLicenseSummary($softwares),
        ]);

        // Sync group quantity and costs for affected groups
        $oldGroupId = (int) ($asset['group_id'] ?? 0);
        $groupsToSync = array_unique(array_filter([$oldGroupId, $newGroupId]));
        foreach ($groupsToSync as $gid) {
            $total = $this->model->where('group_id', $gid)->countAllResults();
            $this->groupModel->update($gid, ['quantity' => $total]);
            $this->syncGroupCosts($gid);
        }

        return redirect()->to(site_url($this->resolveRoutePrefix() . "/assets/show/{$id}"))->with('success', 'Asset updated successfully.');
    }

    // AJAX: validate assignee by name/email against local users and external API
    public function checkUserApi()
    {
        $query = trim((string) $this->request->getGet('q'));

        if ($query === '') {
            return $this->response->setStatusCode(422)->setJSON([
                'valid'  => false,
                'reason' => 'Please type a name or email to validate.',
            ]);
        }

        $queryLower = mb_strtolower($query);

        $exact = $this->userModel
            ->groupStart()
                ->where('LOWER(name)', $queryLower)
                ->orWhere('LOWER(email)', $queryLower)
            ->groupEnd()
            ->first();

        if ($exact) {
            return $this->response->setJSON([
                'valid'   => true,
                'user_id' => (int) $exact['user_id'],
                'name'    => (string) ($exact['name'] ?? ''),
                'email'   => (string) ($exact['email'] ?? ''),
                'reason'  => 'Valid user found.',
                'source'  => 'local',
            ]);
        }

        $candidates = $this->userModel
            ->groupStart()
                ->like('name', $query)
                ->orLike('email', $query)
            ->groupEnd()
            ->orderBy('name', 'ASC')
            ->findAll(6);

        if (count($candidates) > 0) {
            if (count($candidates) === 1) {
                return $this->response->setStatusCode(409)->setJSON([
                    'valid'  => false,
                    'reason' => 'Possible match found, but not exact. Please pick the user from the dropdown to confirm.',
                    'source' => 'local',
                ]);
            }

            return $this->response->setStatusCode(409)->setJSON([
                'valid'  => false,
                'reason' => 'Multiple users match this input. Please type a more specific name/email or pick from the dropdown.',
                'source' => 'local',
            ]);
        }

        if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
            $apiCheck = $this->checkUserInExternalApi($query);
            if ($apiCheck['exists']) {
                $newUser = $this->userModel->insert([
                    'account_no' => $apiCheck['user_data']['EmployeeNo'] ? $apiCheck['user_data']['EmployeeNo'] : $apiCheck['user_data']['StudentNo'],
                    'name'       => $apiCheck['user_data']['FirstName'] . ' ' . $apiCheck['user_data']['LastName'],
                    'email'      => $apiCheck['user_data']['EmailAddress'] ,
                    'role_id'    => $apiCheck['user_data']['EmployeeNo'] ? 4 : 5, // Default to "Employee" or "External" role for API
                ]);

                $newUserId = $this->userModel->getInsertID();
                $exact = $this->userModel->find($newUserId);

                if($exact) {
                    return $this->response->setJSON([
                    'valid'   => true,
                    'user_id' => (int) $exact['user_id'],
                    'name'    => (string) ($exact['name'] ?? ''),
                    'email'   => (string) ($exact['email'] ?? ''),
                    'reason'  => 'Valid user found.',
                    'source'  => 'local',
            ]);
                }
                return $this->response->setStatusCode(404)->setJSON([
                    'valid'  => false,
                    'reason' => 'Email exists in CheckUser API but no local account was found. Please register/import this user first.',
                    'source' => 'external',
                ]);
            }

            return $this->response->setStatusCode(404)->setJSON([
                'valid'  => false,
                'reason' => $apiCheck['reason'] ?: 'No user found for this email in local records or CheckUser API.',
                'source' => 'external',
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'valid'  => false,
            'reason' => 'No user found. Please enter a valid full name or email.',
            'source' => 'local',
        ]);
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

    private function normalizeSoftwareRows($rows): array
    {
        if (! is_array($rows)) {
            return [];
        }

        $clean = [];
        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $clean[] = [
                'name'           => $name,
                'license_type'   => trim((string) ($row['license_type'] ?? '')),
                'license_expiry' => trim((string) ($row['license_expiry'] ?? '')),
                'last_updated'   => trim((string) ($row['last_updated'] ?? '')),
                'is_updated'     => (int) ($row['is_updated'] ?? 0),
                'notes'          => trim((string) ($row['notes'] ?? '')),
            ];
        }

        return $clean;
    }

    private function buildSoftwareLicenseSummary(array $softwares): ?string
    {
        if ($softwares === []) {
            return null;
        }

        $types = [];
        foreach ($softwares as $software) {
            $type = trim((string) ($software['license_type'] ?? ''));
            if ($type !== '') {
                $types[] = $type;
            }
        }

        if ($types === []) {
            return null;
        }

        $types = array_values(array_unique($types));
        return implode(', ', $types);
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

    private function checkUserInExternalApi(string $email): array
    {
        $authId   = (string) env('UNISAP_AUTH_ID');
        $apiToken = (string) env('UNISAP_API_TOKEN');
        $base     = (env('USE_TEST_API') === 'true') ? (string) env('TEST_API_ENDPOINT') : (string) env('UNISAP_API_ENDPOINT');

        if ($authId === '' || $apiToken === '' || $base === '') {
            return ['exists' => false, 'reason' => 'CheckUser API is not configured in this environment.'];
        }

        $client = service('curlrequest');
        $headers = [
            'Auth-ID'       => $authId,
            'Authorization' => $apiToken,
        ];

        $endpoints = [];
        if (str_ends_with(strtolower($email), '@cspc.edu.ph')) {
            $endpoints[] = 'EmployeeInfoByEmail/' . rawurlencode($email);
        } elseif (str_ends_with(strtolower($email), '@my.cspc.edu.ph')) {
            $endpoints[] = 'StudentInfoByEmail/' . rawurlencode($email);
        } else {
            return ['status' => 'error', 'message' => 'Invalid email domain. Only @cspc.edu.ph and @my.cspc.edu.ph are allowed for CheckUser API validation.'];
        }

        foreach ($endpoints as $path) {
            try {
                $res  = $client->get(rtrim($base, '/') . '/' . $path, [
                    'http_errors' => false,
                    'headers'     => $headers,
                ]);
                $jsonResponse = json_decode($res->getBody(), true);
                log_message('debug', 'Assets::checkUserInExternalApi response for {0}: {1}', [$email, json_encode($jsonResponse)]);
                if ($jsonResponse['status'] == '200' && str_ends_with(strtolower($email), '@cspc.edu.ph')) {
                    return ['user_data' => $jsonResponse['EmployeeInfo'], 'exists' => true];
                } else if ($jsonResponse['status'] == '200' && str_ends_with(strtolower($email), '@my.cspc.edu.ph')) {
                    return ['user_data' => $jsonResponse['StudentInfo'], 'exists' => true];
                } else {
                    log_message('info', 'Assets::checkUserInExternalApi no match for {0} at endpoint {1}', [$email, $path]);
                    return ['exists' => false, 'reason' => 'No matching account found in CheckUser API.'];
                }
            } catch (\Throwable $e) {
                log_message('warning', 'Assets::checkUserInExternalApi failed: {0}', [$e->getMessage()]);
                return ['exists' => false, 'reason' => 'No matching account found in CheckUser API.'];
            }
        }

        return ['exists' => false, 'reason' => 'No matching account found in CheckUser API.'];
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
