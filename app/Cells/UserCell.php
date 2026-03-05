<?php

namespace App\Cells;

use App\Models\UserModel;

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

        $data = [
            'user' => $this->userModel->find($user['user_id']),
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