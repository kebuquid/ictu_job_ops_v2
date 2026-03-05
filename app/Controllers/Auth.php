<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use League\OAuth2\Client\Provider\Google;
use App\Models\UserModel;
use App\Enums\UserRole;

class Auth extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('login');
    }
    
    private function getGoogleProvider()
    {
        return new Google([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => base_url('auth/google/callback'),
        ]);
    }

    public function google()
    {
        $provider = $this->getGoogleProvider();

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile']
        ]);

        session()->set('oauth2state', $provider->getState());

        return redirect()->to($authUrl);
    }

    public function googleCallback()
    {
        $provider = $this->getGoogleProvider();

        $sessionState = session()->get('oauth2state');
        $getState = $this->request->getGet('state');

        if (empty($getState) || ($getState !== $sessionState)) {
            session()->remove('oauth2state');
            exit('Invalid state');
        }

        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getGet('code')
            ]);

            /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
            $googleUser = $provider->getResourceOwner($token);

            $userData = [
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar'=> $googleUser->getAvatar(),
            ];


            $userData['user_id'] = $this->checkUser($userData);

            if (!$userData['user_id']) {
                return redirect()->back()->with('error', 'Failed to save user data');
            }

            $user = $this->userModel->getUserWithRole($userData['user_id']);
            session()->set('user', $user);

            $role = UserRole::from($user['role_id']);
            return redirect()->to($role->url_path() . '/dashboard');

        } catch (\Exception $e) {
            exit('Authentication failed: ' . $e->getMessage());
        }
    }

    public function checkUser($userData)
    {
        try{   
            $user = $this->userModel->where('email', $userData['email'])->first();

            if (!$user) {
                if(str_ends_with($userData['email'], '@cspc.edu.ph')) {
                    $userData['role_id'] = UserRole::EMPLOYEE->value; // Employee role
                } else {
                    $userData['role_id'] = UserRole::STUDENT->value;
                }
                $this->userModel->insert($userData);
                return $this->userModel->getInsertID();
            }

            return $user['user_id'];
        }catch(\Exception $e) {
            log_message('error', 'Error checking user: ' . $e->getMessage());
            return null;
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
