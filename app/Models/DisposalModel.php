<?php

namespace App\Models;

use CodeIgniter\Model;

class DisposalModel extends Model
{
    protected $table            = 'asset_disposals';
    protected $primaryKey       = 'disposal_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'asset_id',
        'disposal_reason',
        'disposal_date',
        'approved_by',
        'condition_status',
        'disposal_image',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * All disposal records joined with their asset.
     */
    public function withAsset(): array
    {
        return $this->select('asset_disposals.*, assets.asset_tag, assets.brand_model, assets.serial_number, assets.category')
                    ->join('assets', 'assets.asset_id = asset_disposals.asset_id', 'left')
                    ->orderBy('asset_disposals.disposal_date', 'DESC')
                    ->findAll();
    }

    /**
     * Search disposal records by keyword.
     */
    public function search(string $keyword): array
    {
        return $this->select('asset_disposals.*, assets.asset_tag, assets.brand_model, assets.serial_number, assets.category')
                    ->join('assets', 'assets.asset_id = asset_disposals.asset_id', 'left')
                    ->groupStart()
                        ->like('assets.asset_tag', $keyword)
                        ->orLike('assets.brand_model', $keyword)
                        ->orLike('asset_disposals.disposal_reason', $keyword)
                        ->orLike('asset_disposals.condition_status', $keyword)
                    ->groupEnd()
                    ->orderBy('asset_disposals.disposal_date', 'DESC')
                    ->findAll();
    }

    /**
     * Fetch a single disposal record joined with its asset.
     */
    public function findWithAsset(int $id): ?array
    {
        $row = $this->select('asset_disposals.*, assets.asset_tag, assets.brand_model, assets.serial_number, assets.category')
                    ->join('assets', 'assets.asset_id = asset_disposals.asset_id', 'left')
                    ->where('asset_disposals.disposal_id', $id)
                    ->first();

        return $row ?: null;
    }
}
