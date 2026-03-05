<?php

namespace App\Models;

use CodeIgniter\Model;

class IssueTypeModel extends Model
{
    protected $table            = 'issue_types';
    protected $primaryKey       = 'issue_type_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['issue_type_name', 'issue_type_domain', 'description', 'section_id'];
}
