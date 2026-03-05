<?php

namespace App\Models;

use CodeIgniter\Model;

class JobTicketModel extends Model
{
    protected $table            = 'job_tickets';
    protected $primaryKey       = 'job_ticket_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['requestor_id', 'requestor_name', 'job_status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
