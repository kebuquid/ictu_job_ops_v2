<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestPlatformModel extends Model
{
    protected $table            = 'request_platforms';
    protected $primaryKey       = 'platform_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['platform_name', 'platform_description', 'request_type_id'];
}
