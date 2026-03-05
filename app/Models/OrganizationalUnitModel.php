<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationalUnitModel extends Model
{
    protected $table            = 'organizational_units';
    protected $primaryKey       = 'unit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'building_id'];
}
