<?php

namespace App\Models;

use App\Enums\JobStatus;
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

    /**
     * Build ticket options for dropdowns/select fields.
     */
    public function forSelect(int $limit = 100): array
    {
        $rows = $this->select('job_ticket_id, requestor_name, job_status, created_at')
            ->whereIn('job_status', [
                JobStatus::OPEN->value,
                JobStatus::IN_PROGRESS->value,
                JobStatus::WAITING_FOR_PARTS->value,
            ])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        foreach ($rows as &$row) {
            $row['label'] = sprintf(
                'ICTU-%s-%05d%s',
                date('Y', strtotime((string) ($row['created_at'] ?? 'now'))),
                (int) $row['job_ticket_id'],
                ! empty($row['requestor_name']) ? ' - ' . $row['requestor_name'] : ''
            );
        }
        unset($row);

        return $rows;
    }
}
