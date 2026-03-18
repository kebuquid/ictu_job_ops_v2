<?php

namespace App\Controllers;

use App\Models\DisposalModel;
use App\Models\AssetModel;
use App\Models\UserModel;
use App\Models\KeywordRuleModel;

class Disposals extends BaseController
{
    protected DisposalModel $model;
    protected AssetModel $assetModel;
    protected UserModel $userModel;
    protected KeywordRuleModel $keywordRuleModel;

    public function __construct()
    {
        $this->model            = new DisposalModel();
        $this->assetModel       = new AssetModel();
        $this->userModel        = new UserModel();
        $this->keywordRuleModel = new KeywordRuleModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        $keyword = $this->request->getGet('q');

        $records = $keyword
            ? $this->model->search($keyword)
            : $this->model->withAsset();

        $stats = [
            'total'      => $this->model->countAll(),
            'this_month' => $this->model->where('MONTH(disposal_date)', date('m'))
                                        ->where('YEAR(disposal_date)', date('Y'))
                                        ->countAllResults(),
            'assets'     => $this->model->select('asset_id')->distinct()->countAllResults(),
        ];

        return view('disposals/index', [
            'records' => $records,
            'keyword' => $keyword,
            'stats'   => $stats,
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function create(): string
    {
        $assets = $this->assetModel->orderBy('asset_tag')->findAll();

        return view('disposals/create', [
            'validation'       => \Config\Services::validation(),
            'assets'           => $assets,
            'assetId'          => $this->request->getGet('asset_id'),
            'users'            => $this->userModel->orderBy('name')->findAll(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            'routePrefix'      => $this->resolveRoutePrefix(),
        ]);
    }

    public function store()
    {
        $rules = [
            'asset_id'         => 'required|integer',
            'disposal_date'    => 'required|valid_date',
            'condition_status' => 'required',
            'approved_by'      => 'required|integer',
            'disposal_reason'  => 'required',
        ];

        if (! $this->validate($rules)) {
            $assets = $this->assetModel->orderBy('asset_tag')->findAll();
            return view('disposals/create', [
                'errors'           => $this->validator->getErrors(),
                'assets'           => $assets,
                'assetId'          => $this->request->getPost('asset_id'),
                'users'            => $this->userModel->orderBy('name')->findAll(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
                'routePrefix'      => $this->resolveRoutePrefix(),
            ]);
        }

        $assetId = $this->request->getPost('asset_id');

        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'disposals';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $imageName = null;
        $imageFile = $this->request->getFile('disposal_image');
        if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK && ! $imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
        }

        $this->model->insert([
            'asset_id'         => $assetId,
            'disposal_reason'  => $this->request->getPost('disposal_reason'),
            'disposal_date'    => $this->request->getPost('disposal_date'),
            'approved_by'      => $this->request->getPost('approved_by') ?: null,
            'condition_status' => $this->request->getPost('condition_status'),
            'disposal_image'   => $imageName,
        ]);

        // Auto-update the asset status to Disposed
        $this->assetModel->update($assetId, ['status' => 'Disposed']);

        return redirect()->to(site_url($this->resolveRoutePrefix() . '/disposals'))->with('success', 'Disposal record added and asset marked as Disposed.');
    }

    public function show(int $id): string
    {
        $record = $this->model->findWithAsset($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('disposals/show', [
            'record'      => $record,
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function edit(int $id): string
    {
        $record = $this->model->find($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $assets = $this->assetModel->orderBy('asset_tag')->findAll();

        return view('disposals/edit', [
            'record'           => $record,
            'assets'           => $assets,
            'validation'       => \Config\Services::validation(),
            'users'            => $this->userModel->orderBy('name')->findAll(),
            'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
            'routePrefix'      => $this->resolveRoutePrefix(),
        ]);
    }

    public function update(int $id)
    {
        $record = $this->model->find($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'asset_id'     => 'required|integer',
            'disposal_date' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            $assets = $this->assetModel->orderBy('asset_tag')->findAll();
            return view('disposals/edit', [
                'record'           => $record,
                'assets'           => $assets,
                'validation'       => $this->validator,
                'users'            => $this->userModel->orderBy('name')->findAll(),
                'keywordRulesData' => $this->keywordRuleModel->getGroupedRulesForForm(),
                'routePrefix'      => $this->resolveRoutePrefix(),
            ]);
        }

        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'disposals';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $updateData = [
            'asset_id'         => $this->request->getPost('asset_id'),
            'disposal_reason'  => $this->request->getPost('disposal_reason'),
            'disposal_date'    => $this->request->getPost('disposal_date'),
            'approved_by'      => $this->request->getPost('approved_by') ?: null,
            'condition_status' => $this->request->getPost('condition_status'),
        ];

        $imageFile = $this->request->getFile('disposal_image');
        if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK && ! $imageFile->hasMoved()) {
            if (! empty($record['disposal_image'])) {
                $oldPath = $uploadPath . DIRECTORY_SEPARATOR . $record['disposal_image'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
            $updateData['disposal_image'] = $imageName;
        } else {
            $existing = $this->request->getPost('existing_disposal_image');
            if ($existing) {
                $existPath = $uploadPath . DIRECTORY_SEPARATOR . $existing;
                $updateData['disposal_image'] = is_file($existPath) ? $existing : null;
            }
        }

        $this->model->update($id, $updateData);

        return redirect()->to(site_url($this->resolveRoutePrefix() . "/disposals/show/{$id}"))->with('success', 'Disposal record updated.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to(site_url($this->resolveRoutePrefix() . '/disposals'))->with('success', 'Disposal record deleted.');
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
