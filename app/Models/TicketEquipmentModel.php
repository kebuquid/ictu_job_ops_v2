<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketEquipmentModel extends Model
{
    protected $table            = 'ticket_equipments';
    protected $primaryKey       = 'equipment_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'section_id'];
}
