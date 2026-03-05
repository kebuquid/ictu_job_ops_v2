<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpertiseSignalMapModel extends Model
{
    protected $table            = 'expertise_signal_map';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['expertise_id', 'signal_type', 'signal_value'];
    protected $useTimestamps    = false;

    /**
     * Find matching expertise IDs given a set of ticket signals.
     *
     * @param array $signals  e.g. ['equipment' => [4], 'platform' => [2], 'issue_type' => [1,3], ...]
     * @return array  Array of ['expertise_id' => int, 'match_count' => int] sorted desc by match_count
     */
    public function findMatchingExpertise(array $signals): array
    {
        // Build a flat list of (signal_type, signal_value) pairs
        $conditions = [];
        foreach ($signals as $type => $values) {
            foreach ((array) $values as $val) {
                if (! empty($val)) {
                    $conditions[] = ['type' => $type, 'value' => (int) $val];
                }
            }
        }

        if (empty($conditions)) {
            return [];
        }

        // Query: find all expertise_ids that match any of the signals
        $builder = $this->db->table($this->table);
        $builder->select('expertise_id, COUNT(*) as match_count');
        $builder->groupStart();
        foreach ($conditions as $i => $cond) {
            if ($i === 0) {
                $builder->groupStart();
            } else {
                $builder->orGroupStart();
            }
            $builder->where('signal_type', $cond['type']);
            $builder->where('signal_value', $cond['value']);
            $builder->groupEnd();
        }
        $builder->groupEnd();
        $builder->groupBy('expertise_id');
        $builder->orderBy('match_count', 'DESC');

        return $builder->get()->getResultArray();
    }
}
