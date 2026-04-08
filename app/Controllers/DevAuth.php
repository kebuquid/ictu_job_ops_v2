<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\RoleModel;

class DevAuth extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginPage()
    {
        if(env('CI_ENVIRONMENT') !== 'development') {
            return redirect()->back()->with('error', 'This page is only available in development environment.');
        }

        return view('dev_login');
    }

    public function login($user)
    {
        if(env('CI_ENVIRONMENT') !== 'development') {
            return redirect()->back()->with('error', 'This login method is only available in development environment.');
        }

        try {
            $email = $user . '@cspc.edu.ph';
            $userData = $this->userModel->where('email', $email)->first();
            if (!$userData) {
                return redirect()->back()->with('error', 'User not found.');
            }

            $user = $this->userModel->getUserWithNoRole($userData['user_id']);
                session()->set('user', $user);

                $roleData = (new RoleModel())->find((int) $user['role_id']);
                return redirect()->to(($roleData['url_path'] ?? '/employee') . '/dashboard');
        } catch (\Throwable $e) {
            log_message('error', 'Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'The system encountered an error. Please try again later.');
        }

        
    }
}
