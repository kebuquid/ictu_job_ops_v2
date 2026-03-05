<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestTypeModel extends Model
{
    protected $table            = 'request_types';
    protected $primaryKey       = 'request_type_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['request_type_name', 'section_id'];
}
