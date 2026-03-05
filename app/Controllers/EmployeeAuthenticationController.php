<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class EmployeeAuthenticationController extends BaseController
{
    // private string $clientId;
    // private string $clientSecret;
    private string $authID;
    private string $apiToken;
    private string $validationEndpoint;

    public function __construct()
    {
        $this->authID = env('UNISAP_AUTH_ID');
        $this->apiToken = env('UNISAP_API_TOKEN');
        $this->validationEndpoint = 'https://profile.cspc.edu.ph/Api/';
    }

    public function verify()
    {
        $employeeNumber = $this->request->getPost('employee_number');

        if (empty($employeeNumber)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'Employee number is required.'
                                  ]);
        }

        $unisapResponse = $this->callValidationApi($employeeNumber);

        if ($unisapResponse['status'] !== 200) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => $unisapResponse['message']
                                  ]);
        }


        log_message('debug', 'UNISAP API Response: ' . print_r($unisapResponse['EmployeeInfo'], true));

        if(!empty($unisapResponse['EmployeeInfo']['EmailAddress'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                                  ->setJSON([
                                      'status'  => 'has_email',
                                      'message' => 'This employee number is already associated with an email. Email: ' . $unisapResponse['EmployeeInfo']['EmailAddress'] . '. Please use that email to log in or contact support if you need assistance.'
                                  ]);
        }


        // Store employee data in session for the next verification step
        session()->set('pending_employee', $unisapResponse['EmployeeInfo']);

        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                              ->setJSON([
                                  'status'  => 'success',
                                  'message' => 'Employee found. Please verify your identity.'
                              ]);
    }

    private function callValidationApi(string $employeeNumber): array
    {
        $client = service('curlrequest');
        
        $response = $client->get($this->validationEndpoint . 'EmployeeInfoByEmployeeNo/' . $employeeNumber, [
            'http_errors' => false,
            'headers' => [
                'Auth-ID' => $this->authID,
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() == 404) {
            return [
                'success' => false,
                'message' => 'No employee found with that number. Please check and try again.'
            ];
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    public function verifyUserData()
    {
        $employeeData = session()->get('pending_employee');

        if (empty($employeeData)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'Session expired. Please verify your employee number again.'
                                  ]);
        }

        $firstName = $this->request->getPost('first_name');
        $middleName = $this->request->getPost('middle_name');
        $lastName  = $this->request->getPost('last_name');
        $birthDate = $this->request->getPost('birth_date');

        if (empty($firstName) || empty($middleName) || empty($lastName) || empty($birthDate)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'All fields (first name, middle name, last name, and birthdate) are required.'
                                  ]);
        }

        // Check if the submitted data matches the stored employee record
        if (strtolower(trim($firstName)) !== strtolower(trim($employeeData['FirstName']))
            || strtolower(trim($middleName)) !== strtolower(trim($employeeData['MiddleName'])) 
            || strtolower(trim($lastName)) !== strtolower(trim($employeeData['LastName'])) 
            || $birthDate !== $employeeData['DateOfBirth']) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'The first name, middle name, last name, or birthdate does not match our records.'
                                  ]);
        }

        // Identity verified, no email on record — allow account creation
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                              ->setJSON([
                                  'status'  => 'success',
                                  'message' => 'Identity verified. You may now create your organizational account.'
                              ]);
    }

    public function checkEmailIfExists($email)
    {
        $client = service('curlrequest');

        $emailResponse = $client->get($this->validationEndpoint . 'EmployeeInfoByEmail/' . $email, [
            'http_errors' => false,
            'headers' => [
                'Auth-ID' => $this->authID,
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($emailResponse->getStatusCode() == 404) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON(['message' => 'Email is available']);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                              ->setJSON(['message' => 'Email already taken.']);
    }

    public function getEmailSuggestion()
    {
       try{
        $client = service('curlrequest');
        $employeeData = session()->get('pending_employee');
        if (empty($employeeData)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'Session expired. Please verify your employee number again.'
                                  ]);
        }
        $first = strtolower($employeeData['FirstName']);
        $last = strtolower($employeeData['LastName']);
        $fi = substr($first, 0, 2);
        $fir = substr($first, 0, 3);

        $suggestion = [
            "{$first}{$last}@cspc.edu.ph",           // johndoe
            "{$first}.{$last}@cspc.edu.ph",          // john.doe
            "{$fi}{$last}@cspc.edu.ph",               // jodoe
            "{$fir}{$last}@cspc.edu.ph",              // johdoe
            "{$first}_{$last}@cspc.edu.ph",          // john_doe
            "{$fi}.{$last}@cspc.edu.ph",            //jo.doe
            "{$fir}.{$last}@cspc.edu.ph",           //joh.doe
        ];

        $validSuggestions = [];
        
        foreach ($suggestion as $email) {
            $emailResponse = $client->get($this->validationEndpoint . 'EmployeeInfoByEmail/' . $email, [
                'http_errors' => false ,
                'headers' => [
                    'Auth-ID' => $this->authID,
                    'Authorization' => $this->apiToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            if($emailResponse->getStatusCode() == 404) {
                 array_push($validSuggestions, $email);
            }
        }
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                              ->setJSON(['suggestions' => $validSuggestions]);
       }catch(\Exception $e) {
        return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                              ->setJSON(['message' => 'Failed to generate email suggestions. Please try again later.' . $e->getMessage()]);
       }
    }

    public function createEmail()
    {
        $employeeData = session()->get('pending_employee');
        $newAccount['email'] = $this->request->getPost('email');
        $newAccount['password'] = $this->request->getPost('password');
        $newAccount['confirm_password'] = $this->request->getPost('confirm_password');

        if(empty($newAccount['email']) || empty($newAccount['password'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['message' => 'Email and password are required.']);
        }

        if($newAccount['password'] !== $newAccount['confirm_password']) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['message' => 'Password and confirm password do not match.']);
        }

        if(str_ends_with($newAccount['email'], '@cspc.edu.ph') === false) {
            $newAccount['email'] .= '@cspc.edu.ph';
        }

        $client = service('curlrequest');

        $emailResponse = $client->get($this->validationEndpoint . 'EmployeeInfoByEmail/' . $newAccount['email'], [
            'http_errors' => false,
            'headers' => [
                'Auth-ID' => $this->authID,
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($emailResponse->getStatusCode() == 200) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['message' => 'Email is already taken']);
        }

        $accountRequest = $client->post($this->validationEndpoint . 'register-account', [
            'headers' => [
                'Auth-ID' => $this->authID,
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'employeeNumber' => $employeeData['EmployeeNo'],
                'email' => $newAccount['email'],
                'password' => $newAccount['password']
            ]
        ]);

        if($accountRequest->getStatusCode() == 201) {
            // Clear pending employee session data after successful account creation
            session()->remove('pending_employee');

            return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)
                                  ->setJSON(['message' => 'Account created successfully. You can now log in with your new email and password.']);
        } else {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                  ->setJSON(['message' => 'Failed to create account. Please try again later.'. $accountRequest->getBody()]);
        }
    }


}
