<?php

namespace App\Controllers;

use App\Models\AssetGroupModel;
use App\Models\AssetModel;
use App\Models\BuildingModel;
use App\Models\OrganizationalUnitModel;
use App\Models\KeywordRuleModel;

class AssetGroups extends BaseController
{
    protected AssetGroupModel $groupModel;
    protected AssetModel $assetModel;
    protected BuildingModel $buildingModel;
    protected OrganizationalUnitModel $unitModel;
    protected KeywordRuleModel $keywordRuleModel;

    public function __construct()
    {
        $this->groupModel        = new AssetGroupModel();
        $this->assetModel        = new AssetModel();
        $this->buildingModel     = new BuildingModel();
        $this->unitModel         = new OrganizationalUnitModel();
        $this->keywordRuleModel  = new KeywordRuleModel();
        helper(['form', 'url']);
    }

    // LIST
    public function index(): string
    {
        $perPage = 10;
        $groups  = $this->groupModel->orderBy('created_at', 'DESC')->paginate($perPage, 'groups');
        $pager   = $this->groupModel->pager;
        $total   = $this->groupModel->countAll();

        foreach ($groups as &$g) {
            $g['asset_count'] = $this->assetModel
                ->where('group_id', $g['group_id'])
                ->countAllResults();
        }
        unset($g);

        return view('asset_groups/index', [
            'groups'       => $groups,
            'pager'        => $pager,
            'total'        => $total,
            'routePrefix'  => $this->resolveRoutePrefix(),
        ]);
    }

    // CREATE FORM
    public function create(): string
    {
        return view('asset_groups/create', [
            'buildings'        => $this->buildingModel->orderBy('name')->findAll(),
            'units'            => $this->unitModel->orderBy('name')->findAll(),
            'availableAssets'  => $this->assetModel->where('group_id IS NULL')->orderBy('asset_tag', 'ASC')->findAll(),
            'users'            => model('App\Models\UserModel')->orderBy('name')->findAll(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            'routePrefix'      => $this->resolveRoutePrefix(),
        ]);
    }

    // STORE
    public function store()
    {
        $rules = [
            'group_name'       => 'required|max_length[200]',
            'group_code'       => 'required|max_length[50]',
            'category'         => 'required',
            'tag_prefix'       => 'required|max_length[20]',
            'lifecycle'        => 'required',
            'building_id'      => 'required|integer',
            'assigned_unit_id' => 'required|integer',
            'assigned_to'      => 'permit_empty',
            'description'      => 'required',
            'status'           => 'required|in_list[Active,Inactive,Under Repair,Disposed]',
        ];

        if (! $this->validate($rules)) {
            return view('asset_groups/create', [
                'validation'      => $this->validator,
                'buildings'       => $this->buildingModel->orderBy('name')->findAll(),
                'units'           => $this->unitModel->orderBy('name')->findAll(),
                'availableAssets' => $this->assetModel->where('group_id IS NULL')->orderBy('asset_tag', 'ASC')->findAll(),
                'users'           => model('App\Models\UserModel')->orderBy('name')->findAll(),
                'keywordRulesData'=> $this->keywordRuleModel->getGroupedRulesForForm(),
                'routePrefix'     => $this->resolveRoutePrefix(),
            ]);
        }

        $groupId = $this->groupModel->insert([
            'group_name'        => $this->request->getPost('group_name'),
            'group_code'        => $this->request->getPost('group_code') ?: null,
            'category'          => $this->request->getPost('category'),
            'description'       => $this->request->getPost('description') ?: null,
            'quantity'          => 0,
            'tag_prefix'        => strtoupper(trim($this->request->getPost('tag_prefix') ?? '')) ?: null,
            'assigned_unit_id'  => $this->request->getPost('assigned_unit_id') ?: null,
            'assigned_to'       => $this->request->getPost('assigned_to') ?: null,
            'date_acquired'     => $this->request->getPost('date_acquired') ?: null,
            'acquisition_cost'  => $this->request->getPost('acquisition_cost') ?: null,
            'depreciation_cost' => $this->request->getPost('depreciation_cost') ?: null,
            'warranty_end'      => $this->request->getPost('warranty_end') ?: null,
            'status'            => $this->request->getPost('status') ?: 'Active',
            'lifecycle'         => $this->request->getPost('lifecycle') ?: null,
        ], true);

        // Assign selected assets right away
        $assetIds = $this->request->getPost('asset_ids') ?? [];
        if (! is_array($assetIds)) $assetIds = [$assetIds];
        $assetIds = array_filter(array_map('intval', $assetIds));
        foreach ($assetIds as $assetId) {
            $this->assetModel->update($assetId, ['group_id' => $groupId]);
        }
        if (count($assetIds)) {
            $total = $this->assetModel->where('group_id', $groupId)->countAllResults();
            $this->groupModel->update($groupId, ['quantity' => $total]);
        }

        $msg = count($assetIds)
            ? 'Group created with ' . count($assetIds) . ' asset(s) assigned.'
            : 'Group created! You can assign assets from this page.';

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $groupId))
            ->with('success', $msg);
    }

    /**
     * Recompute and save average acquisition/depreciation costs for a group
     * based on its currently linked assets (ignoring assets with zero costs).
     */
    private function syncGroupCosts(int $groupId): void
    {
        $assets = $this->assetModel->where('group_id', $groupId)->findAll();
        $acqValues  = array_filter(array_column($assets, 'acquisition_cost'),  fn($v) => (float)$v > 0);
        $deprValues = array_filter(array_column($assets, 'depreciation_cost'), fn($v) => (float)$v > 0);
        $avgAcq  = count($acqValues)  ? array_sum($acqValues)  / count($acqValues)  : 0;
        $avgDepr = count($deprValues) ? array_sum($deprValues) / count($deprValues) : 0;
        $this->groupModel->update($groupId, [
            'acquisition_cost'  => round($avgAcq,  2),
            'depreciation_cost' => round($avgDepr, 2),
        ]);
    }

    // ASSIGN EXISTING ASSET(S) TO GROUP
    public function assignAsset(int $id)
    {
        $group = $this->groupModel->find($id);
        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Group #{$id} not found.");
        }

        $assetIds = $this->request->getPost('asset_ids') ?? [];
        if (! is_array($assetIds)) $assetIds = [$assetIds];
        $assetIds = array_filter(array_map('intval', $assetIds));

        $count = 0;
        foreach ($assetIds as $assetId) {
            $asset = $this->assetModel->find($assetId);
            if ($asset) {
                $this->assetModel->update($assetId, ['group_id' => $id]);
                $count++;
            }
        }

        // Sync quantity and costs on group record
        $total = $this->assetModel->where('group_id', $id)->countAllResults();
        $this->groupModel->update($id, ['quantity' => $total]);
        $this->syncGroupCosts($id);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $id))
            ->with('success', "{$count} asset(s) assigned to this group.");
    }

    // REMOVE ASSET FROM GROUP
    public function removeAsset(int $groupId, int $assetId)
    {
        $this->assetModel->update($assetId, ['group_id' => null]);

        // Sync quantity and costs
        $total = $this->assetModel->where('group_id', $groupId)->countAllResults();
        $this->groupModel->update($groupId, ['quantity' => $total]);
        $this->syncGroupCosts($groupId);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $groupId))
            ->with('success', 'Asset removed from group.');
    }

    // SHOW GROUP + its assets
    public function show(int $id): string
    {
        $group = $this->groupModel->find($id);
        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Group #{$id} not found.");
        }

        $assets = $this->assetModel
            ->where('group_id', $id)
            ->orderBy('asset_tag', 'ASC')
            ->findAll();

        // Auto-sync group costs if currently 0 but assets have cost data
        if ((float)($group['acquisition_cost'] ?? 0) == 0 && (float)($group['depreciation_cost'] ?? 0) == 0) {
            $this->syncGroupCosts($id);
            $group = $this->groupModel->find($id); // reload with updated costs
        }

        // Only unassigned assets for the assign panel
        $availableAssets = $this->assetModel
            ->where('group_id', null)
            ->orderBy('asset_tag', 'ASC')
            ->findAll();

        $unitName     = '—';
        $buildingName = '—';
        if ($group['assigned_unit_id']) {
            $unit = $this->unitModel->find($group['assigned_unit_id']);
            if ($unit) {
                $unitName = $unit['name'];
                $building = $this->buildingModel->find($unit['building_id'] ?? 0);
                if ($building) $buildingName = $building['name'];
            }
        }

        return view('asset_groups/show', [
            'group'           => $group,
            'assets'          => $assets,
            'availableAssets' => $availableAssets,
            'otherGroups'     => $this->groupModel
                                    ->where('group_id !=', $id)
                                    ->orderBy('group_name')
                                    ->findAll(),
            'unitName'        => $unitName,
            'buildingName'    => $buildingName,
            'routePrefix'     => $this->resolveRoutePrefix(),
        ]);
    }

    // TRANSFER ASSET TO ANOTHER GROUP
    public function transferAsset(int $groupId, int $assetId)
    {
        $targetGroupId = (int) $this->request->getPost('target_group_id');

        if (! $this->groupModel->find($targetGroupId)) {
            return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $groupId))
                ->with('error', 'Target group not found.');
        }

        $this->assetModel->update($assetId, ['group_id' => $targetGroupId]);

        // Sync quantity and costs on both groups
        foreach ([$groupId, $targetGroupId] as $gid) {
            $total = $this->assetModel->where('group_id', $gid)->countAllResults();
            $this->groupModel->update($gid, ['quantity' => $total]);
            $this->syncGroupCosts($gid);
        }

        $target = $this->groupModel->find($targetGroupId);
        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $groupId))
            ->with('success', 'Asset transferred to "' . $target['group_name'] . '" successfully.');
    }

    // EDIT FORM
    public function edit(int $id): string
    {
        $group = $this->groupModel->find($id);
        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Group #{$id} not found.");
        }

        // Resolve current building from assigned_unit_id
        $currentBuildingId = null;
        if ($group['assigned_unit_id']) {
            $unit = $this->unitModel->find($group['assigned_unit_id']);
            if ($unit) $currentBuildingId = $unit['building_id'];
        }

        return view('asset_groups/edit', [
            'group'             => $group,
            'buildings'         => $this->buildingModel->orderBy('name')->findAll(),
            'units'             => $this->unitModel->orderBy('name')->findAll(),
            'currentBuildingId' => $currentBuildingId,
            'routePrefix'       => $this->resolveRoutePrefix(),
        ]);
    }

    // UPDATE
    public function update(int $id)
    {
        $group = $this->groupModel->find($id);
        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Group #{$id} not found.");
        }

        $this->groupModel->update($id, [
            'group_name'        => $this->request->getPost('group_name'),
            'group_code'        => $this->request->getPost('group_code') ?: null,
            'category'          => $this->request->getPost('category'),
            'description'       => $this->request->getPost('description') ?: null,
            'tag_prefix'        => strtoupper(trim($this->request->getPost('tag_prefix') ?? '')) ?: null,
            'assigned_unit_id'  => $this->request->getPost('assigned_unit_id') ?: null,
            'assigned_to'       => $this->request->getPost('assigned_to') ?: null,
            'date_acquired'     => $this->request->getPost('date_acquired') ?: null,
            'acquisition_cost'  => $this->request->getPost('acquisition_cost') ?: null,
            'depreciation_cost' => $this->request->getPost('depreciation_cost') ?: null,
            'warranty_end'      => $this->request->getPost('warranty_end') ?: null,
            'status'            => $this->request->getPost('status') ?: 'Active',
            'lifecycle'         => $this->request->getPost('lifecycle') ?: null,
        ]);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups/show/' . $id))
            ->with('success', 'Group updated successfully.');
    }

    // DELETE GROUP + its assets
    public function delete(int $id)
    {
        $group = $this->groupModel->find($id);
        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Group #{$id} not found.");
        }

        $this->assetModel->where('group_id', $id)->set(['group_id' => null])->update();
        $this->groupModel->delete($id);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/asset-groups'))
            ->with('success', 'Group deleted. Individual assets were unlinked (not deleted).');
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
