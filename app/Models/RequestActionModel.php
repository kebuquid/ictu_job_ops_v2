<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestActionModel extends Model
{
    protected $table            = 'request_actions';
    protected $primaryKey       = 'action_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['action_name', 'request_type_id', 'section_id'];
}
