<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetGroupModel extends Model
{
    protected $table            = 'asset_groups';
    protected $primaryKey       = 'group_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'group_name',
        'group_code',
        'category',
        'description',
        'quantity',
        'tag_prefix',
        'section_id',
        'assigned_unit_id',
        'assigned_to',
        'date_acquired',
        'acquisition_cost',
        'depreciation_cost',
        'warranty_end',
        'lifecycle',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
