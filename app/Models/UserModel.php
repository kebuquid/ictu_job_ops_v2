<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Enums\UserRole;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'phone_number', 'avatar', 'expertise', 'section_id', 'role_id'];


    /**
     * Get user with role enum
     */
    public function getUserWithRole(int $userId)
    {
        $user = $this->find($userId);
        
        if ($user) {
            $user['role_enum'] = UserRole::from($user['role_id']);
            $user['role'] = $user['role_enum']->label();
        }
        
        return $user;
    }

    /**
     * Cast role to enum after fetching
     */
    protected function afterFind(array $data)
    {
        if (isset($data['data'])) {
            foreach ($data['data'] as &$row) {
                if (isset($row['role'])) {
                    $row['role_enum'] = UserRole::from($row['role']);
                }
            }
        } elseif (isset($data['role'])) {
            $data['role_enum'] = UserRole::from($data['role']);
        }

        return $data;
    }

    public function getEmployees()
    {
        $employees =  $this->select('users.*, sections.name as section_name, sections.acronym')
                    ->join('sections', 'users.section_id = sections.section_id', 'left')
                    ->where('users.role_id <', 5) // Assuming role_id < 5 are employees
                    ->findAll();
        // Cast role_id to enum
        foreach ($employees as &$employee) {
            $employee['initials'] = $this->get_initials($employee['name']);
            $employee['role'] = UserRole::from($employee['role_id'])->label();
            if($employee['role'] == UserRole::SUPER_ADMIN->label()) {
                $employee['acronym'] = 'ICTU';
            }
            $employee['role_color'] = UserRole::from($employee['role_id'])->role_color();
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
