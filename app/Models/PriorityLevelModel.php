<?php

namespace App\Models;

use CodeIgniter\Model;

class PriorityLevelModel extends Model
{
    protected $table            = 'priority_levels';
    protected $primaryKey       = 'priority_level_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['priority_name', 'operation_status', 'description'];
}
