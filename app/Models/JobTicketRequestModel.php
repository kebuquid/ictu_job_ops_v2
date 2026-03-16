<?php

namespace App\Models;

use CodeIgniter\Model;

class JobTicketRequestModel extends Model
{
    protected $table            = 'job_ticket_requests';
    protected $primaryKey       = 'job_ticket_request_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_ticket_id',
        'section_id',
        'problem_description',
        'asset_id',
        'pre_repair_form',
        'request_type',
        'request_platform',
        'request_equipment',
        'request_action',
        'peripheral_description',
        'priority_level',
        'hardware_issues',
        'software_issues',
        'additional_details',
        'additional_request_file',
        'verifier_id',
        'verification_date',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
