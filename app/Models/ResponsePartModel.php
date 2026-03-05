<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponsePartModel extends Model
{
    protected $table         = 'response_parts';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['job_ticket_response_id', 'part_type', 'part_name', 'quantity', 'unit_cost'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    /**
     * Get all parts for a specific response.
     */
    public function getByResponseId(int $responseId): array
    {
        return $this->where('job_ticket_response_id', $responseId)
                    ->orderBy('part_type', 'ASC')
                    ->orderBy('part_name', 'ASC')
                    ->findAll();
    }

    /**
     * Save multiple parts for a response (delete existing first, then re-insert).
     */
    public function syncParts(int $responseId, array $parts): void
    {
        // Remove old parts
        $this->where('job_ticket_response_id', $responseId)->delete();

        // Insert new parts
        foreach ($parts as $part) {
            if (empty($part['part_name'])) {
                continue;
            }
            $this->insert([
                'job_ticket_response_id' => $responseId,
                'part_type'              => $part['part_type'] ?? 'used',
                'part_name'              => $part['part_name'],
                'quantity'               => (int) ($part['quantity'] ?? 1),
                'unit_cost'              => !empty($part['unit_cost']) ? (float) $part['unit_cost'] : null,
            ]);
        }
    }
}
