<?php

namespace App\Models;

use CodeIgniter\Model;

class JobTicketResponseModel extends Model
{
    protected $table            = 'job_ticket_responses';
    protected $primaryKey       = 'job_ticket_response_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_ticket_id',
        'control_no',
        'action_performed',
        'estimated_cost',
        'staff_id',
        'transferred_by',
        'transferred_at',
        'start_date',
        'completion_date',
        'completion_status',
        'is_completed_in_timeline',
        'timeliness',
        'quality',
        'communication',
        'responsiveness',
        'overall',
        'additional_comments',
        'feedback_date',
        'verifier_id',
        'verified_date',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
