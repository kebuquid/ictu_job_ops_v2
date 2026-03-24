<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'assets';
    protected $primaryKey       = 'asset_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'asset_tag',
        'property_no',
        'brand_model',
        'serial_number',
        'category',
        'operating_system',
        'os_license_key',
        'os_license_type',
        'os_license_expiry',
        'os_last_updated',
        'os_is_updated',
        'software_installed',
        'software_license',
        'section_id',
        'assigned_to',
        'assigned_unit_id',
        'group_id',
        'date_acquired',
        'acquisition_cost',
        'depreciation_cost',
        'warranty_end',
        'lifecycle',
        'supplier',
        'po_number',
        'invoice_number',
        'procurement_mode',
        'fund_source',
        'status',
        'asset_image',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
