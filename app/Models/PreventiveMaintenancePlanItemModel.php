<?php

namespace App\Models;

use CodeIgniter\Model;

class PreventiveMaintenancePlanItemModel extends Model
{
    protected $table            = 'pm_plan_items';
    protected $primaryKey       = 'item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'plan_id',
        'asset_id',
        'description',
        'frequency',
        'schedule_months',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Return default month array based on frequency keyword.
     * The user can later override in the DB.
     */
    public static function defaultMonths(string $frequency, int $startMonth = 1): array
    {
        return match ($frequency) {
            'monthly'       => range(1, 12),
            'quarterly'     => [
                $startMonth,
                ($startMonth + 2) > 12 ? ($startMonth + 2 - 12) : ($startMonth + 3),
                ($startMonth + 5) > 12 ? ($startMonth + 5 - 12) : ($startMonth + 6),
                ($startMonth + 8) > 12 ? ($startMonth + 8 - 12) : ($startMonth + 9),
            ],
            'semi_annually' => [$startMonth, $startMonth <= 6 ? $startMonth + 6 : $startMonth - 6],
            'annually'      => [$startMonth],
            default         => [],
        };
    }
}
