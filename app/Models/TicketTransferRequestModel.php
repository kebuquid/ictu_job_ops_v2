<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketTransferRequestModel extends Model
{
    protected $table            = 'ticket_transfer_requests';
    protected $primaryKey       = 'transfer_request_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_ticket_response_id',
        'job_ticket_id',
        'requested_by',
        'suggested_staff_id',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
        'approved_staff_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
