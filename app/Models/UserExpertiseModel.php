<?php

namespace App\Models;

use CodeIgniter\Model;

class UserExpertiseModel extends Model
{
    protected $table            = 'user_expertise';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'expertise_id'];
    protected $useTimestamps    = false;
    protected $createdField     = 'created_at';

    /**
     * Sync expertise for a user: delete old, insert new.
     */
    public function syncForUser(int $userId, array $expertiseIds): void
    {
        $this->where('user_id', $userId)->delete();

        foreach ($expertiseIds as $eid) {
            $this->insert([
                'user_id'      => $userId,
                'expertise_id' => (int) $eid,
            ]);
        }
    }

    /**
     * Get expertise IDs for a given user.
     */
    public function getExpertiseIdsForUser(int $userId): array
    {
        return array_column(
            $this->where('user_id', $userId)->findAll(),
            'expertise_id'
        );
    }
}
