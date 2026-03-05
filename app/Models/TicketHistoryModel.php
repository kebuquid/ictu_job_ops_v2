<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketHistoryModel extends Model
{
    protected $table            = 'ticket_history';
    protected $primaryKey       = 'history_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_ticket_id',
        'action',
        'old_status',
        'new_status',
        'performed_by',
        'remarks',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Get full history for a ticket with performer names, ordered chronologically.
     */
    public function getByTicketId(int $ticketId): array
    {
        return $this->select('ticket_history.*, users.name as performer_name')
            ->join('users', 'users.user_id = ticket_history.performed_by', 'left')
            ->where('ticket_history.job_ticket_id', $ticketId)
            ->orderBy('ticket_history.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Convenience method to log a history entry.
     */
    public function log(int $ticketId, string $action, ?int $oldStatus, ?int $newStatus, ?int $performedBy, ?string $remarks = null): void
    {
        $this->insert([
            'job_ticket_id' => $ticketId,
            'action'        => $action,
            'old_status'    => $oldStatus,
            'new_status'    => $newStatus,
            'performed_by'  => $performedBy,
            'remarks'       => $remarks,
        ]);
    }
}
