<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpertiseModel extends Model
{
    protected $table            = 'expertise';
    protected $primaryKey       = 'expertise_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['skill', 'description', 'section_id'];
}
