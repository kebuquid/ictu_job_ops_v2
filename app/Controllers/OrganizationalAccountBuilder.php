<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use Google\Client;
use Google\Service\Directory;
use Google\Service\Directory\User;
use Google\Service\Directory\UserName;

class OrganizationalAccountBuilder extends BaseController
{
    // public function index()
    // {
    //     //
    // }

    public function createWorkspaceUser()
    {
        $employeeData = session()->get('new_employee_data');
        if(empty($employeeData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No employee data found in session.']);
        }
        $client = new Client();
        $client->setAuthConfig(WRITEPATH . 'keys/google-admin-sdk.json');
        $client->setScopes([Directory::ADMIN_DIRECTORY_USER]);
        
        $client->setSubject('@cspc.edu.ph');

        $service = new Directory($client);
        $user = new User();
        $name = new UserName();
        
        $name->setGivenName($employeeData['first_name']);
        $name->setFamilyName($employeeData['last_name']);
        
        $user->setName($name);
        $user->setPrimaryEmail($employeeData['email']);
        $user->setPassword($employeeData['password']);
        $user->setChangePasswordAtNextLogin(true);

        try {
            $results = $service->users->insert($user);
            return $this->response->setJSON(['status' => 'success', 'user' => $results->primaryEmail]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
