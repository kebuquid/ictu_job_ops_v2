<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketSlaRuleModel extends Model
{
    protected $table            = 'ticket_sla_rules';
    protected $primaryKey       = 'sla_rule_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'section_id',
        'request_type_id',
        'platform_id',
        'action_id',
        'equipment_id',
        'target_hours',
        'is_active',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
