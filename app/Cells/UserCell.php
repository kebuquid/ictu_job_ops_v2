<?php

namespace App\Cells;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\SectionModel;

class UserCell
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function displayInfo()
    {
        $user    = session()->get('user');
        $userData = $this->userModel->find($user['user_id']);

        // Add role and section info for admin/ICTU staff
        if ($userData && in_array((int) $userData['role_id'], [1, 2, 3])) {
            $roleData = (new RoleModel())->find((int) $userData['role_id']);
            $userData['role_label'] = $roleData['label'] ?? 'Unassigned';
            $userData['role_color'] = $roleData['role_color'] ?? 'gray';

            if (!empty($userData['section_id'])) {
                $section = (new SectionModel())->find((int) $userData['section_id']);
                $userData['section_acronym'] = $section['acronym'] ?? null;
            }
        }

        $data = [
            'user' => $userData,
        ];

        // This points to the view file we will create next
        return view('cells/user_info', $data);
    }

    public function displayAvatar()
    {
        $user = session()->get('user');

        $data = [
            'user' => $this->userModel->find($user['user_id']),
        ];

        $data['user']['initials'] = $this->userModel->get_initials($user['name']);

        // This points to the view file we will create next
        return view('cells/user_avatar', $data);
    }
}