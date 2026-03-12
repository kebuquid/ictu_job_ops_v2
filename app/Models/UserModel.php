<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\RoleModel;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['account_no', 'name', 'email', 'alt_email', 'phone_number', 'avatar', 'expertise', 'section_id', 'role_id', 'is_ictu_employee'];


    /**
     * Get user with role data from DB
     */
    public function getUserWithRole(int $userId)
    {
        $user = $this->find($userId);

        if ($user) {
            $roleData           = (new RoleModel())->find((int) $user['role_id']);
            $user['role']       = $roleData['label']      ?? 'Unassigned';
            $user['role_color'] = $roleData['role_color'] ?? 'gray';
        }

        return $user;
    }

    public function getEmployees()
    {
        $employees =  $this->select('users.*, sections.name as section_name, sections.acronym')
                    ->join('sections', 'users.section_id = sections.section_id', 'left')
                    ->where('users.role_id <', 4) // ICTU staff: roles 1-3
                    ->findAll();
        $roleModel = new RoleModel();
        foreach ($employees as &$employee) {
            $employee['initials']   = $this->get_initials($employee['name']);
            $roleData               = $roleModel->find((int) $employee['role_id']);
            $employee['role']       = $roleData['label']      ?? 'Unknown';
            $employee['role_color'] = $roleData['role_color'] ?? 'gray';
            if ((int) $employee['role_id'] === 1) {
                $employee['acronym'] = 'ICTU';
            }
        }

        return $employees;
    }

    function get_initials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }

        // Limit to 2 characters (e.g., "John Doe Smith" -> "JD")
        return substr($initials, 0, 2);
    }
}
