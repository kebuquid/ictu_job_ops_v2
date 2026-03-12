<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\SectionModel;
use App\Models\UserModel;
use App\Models\ExpertiseSignalMapModel;
use App\Models\UserExpertiseModel;

class EmployeeAuthenticationController extends BaseController
{
    // private string $clientId;
    // private string $clientSecret;
    private string $authID;
    private string $apiToken;
    private string $validationEndpoint;
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private SectionModel $sectionModel;
    private UserModel $userModel;
    private ExpertiseSignalMapModel $signalMapModel;
    private UserExpertiseModel $userExpertiseModel;

    public function __construct()
    {
        $this->authID = env('UNISAP_AUTH_ID');
        $this->apiToken = env('UNISAP_API_TOKEN');
        $this->validationEndpoint = (env('USE_TEST_API') === 'true') ? env('TEST_API_ENDPOINT') : env('UNISAP_API_ENDPOINT');
        $this->jobTicketModel = new JobTicketModel();
        $this->jobTicketRequestModel = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->sectionModel = new SectionModel();
        $this->userModel = new UserModel();
        $this->signalMapModel = new ExpertiseSignalMapModel();
        $this->userExpertiseModel = new UserExpertiseModel();
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

        if ($unisapResponse['status'] == 404) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => $unisapResponse['message']
                                  ]);
        }


        log_message('debug', 'UNISAP API Response: ' . print_r($unisapResponse['EmployeeInfo'], true));

        // Always store in session so the forgot-account flow can reference it
        session()->set('pending_employee', $unisapResponse['EmployeeInfo']);

        log_message('debug', 'Stored pending_employee in session: ' . print_r(session()->get('pending_employee'), true));

        if(!empty($unisapResponse['EmployeeInfo']['EmailAddress'])) {
            $maskedEmail = $this->maskEmail($unisapResponse['EmployeeInfo']['EmailAddress']);
            return $this->response->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                                  ->setJSON([
                                      'status'  => 'has_email',
                                      'message' => 'This employee number is already associated with an email. <span class="font-medium">Email: ' . $maskedEmail . '</span>. Please use that email to log in or contact support if you need assistance.'
                                  ]);
        }

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

        $arrayResponse = json_decode($response->getBody(), true);
        
        return $arrayResponse;
    }

    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 2) {
            $maskedName = str_repeat('*', strlen($name));
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', strlen($name));
        }

        return $maskedName . '@' . $domain;
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
        $lastName  = $this->request->getPost('last_name');
        $birthDate = $this->request->getPost('birth_date');

        if (empty($firstName) || empty($lastName) || empty($birthDate)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'First name, last name, and birthdate are required.'
                                  ]);
        }

        // Check if the submitted data matches the stored employee record
        if (strtolower(trim($firstName)) !== strtolower(trim($employeeData['FirstName']))
            || strtolower(trim($lastName)) !== strtolower(trim($employeeData['LastName'])) 
            || $birthDate !== $employeeData['DateOfBirth']) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'The first name, last name, or birthdate does not match our records.'
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

        $arrayResponse = json_decode($emailResponse->getBody(), true);

        if ($arrayResponse['status'] == 404) {
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

    public function getAltEmail()
    {
        $client = service('curlrequest');
        $employeeData = session()->get('pending_employee');

        if (empty($employeeData)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'session_expired',
                                      'message' => 'Session expired. Please verify your employee number again.'
                                  ]);
        }

        $getAltEmail = $client->get($this->validationEndpoint . 'GetAltEmail/' . $employeeData['EmployeeNo'], [
            'http_errors' => false,
            'headers' => [
                'Auth-ID' => $this->authID,
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json'
            ]
        ]);

        if($getAltEmail->getStatusCode() === 404) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON([
                                    'status' => 'no_alt_email',
                                    'message' => 'No alternative email found. Please visit the ICTU office for assistance in recovering your account.'
                                    ]);
        }
        if($getAltEmail->getStatusCode() === 200) {
            $recoveryTicket = $this->createAccountRecoveryTicket();
        }

        if($recoveryTicket) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON([
                                    'status' => 'recovery_ticket_created',
                                    'message' => 'A recovery ticket has been created. Please check your alternative email for instructions to recover your account.'
                                    ]);
        } else {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                  ->setJSON([
                                    'status' => 'recovery_ticket_failed',
                                    'message' => 'Failed to create recovery ticket. Please try again later or visit the ICTU office for assistance.'
                                    ]);
        }
    }

    private function createAccountRecoveryTicket(): bool
    {
        $employeeData = session()->get('pending_employee');
        if (empty($employeeData)) {
            return false;
        }

        // Find MIS section
        $section = $this->sectionModel->where('acronym', 'MIS')->first();
        if (empty($section)) {
            log_message('error', 'Account recovery: MIS section not found.');
            return false;
        }
        $sectionId   = (int) $section['section_id'];
        $emailStr    = $employeeData['EmailAddress'] ?? 'N/A';
        $problemDesc = 'Google Account recovery request for employee no. '
            . ($employeeData['EmployeeNo'] ?? '?')
            . ' with email: ' . $emailStr;

        // -- 1. Expertise signal map (mirrors TicketController::autoAssignTicket) --
        $signals = [
            'equipment'    => [],
            'request_type' => [1],
            'platform'     => [2],
            'action'       => [2],
            'issue_type'   => [],
        ];
        $matchedExpertise = $this->signalMapModel->findMatchingExpertise($signals);

        // -- 2. Get all MIS section staff (admin + ICTU staff) --
        $sectionStaff = $this->userModel
            ->whereIn('role_id', [2, 3])
            ->where('section_id', $sectionId)
            ->findAll();

        if (empty($sectionStaff)) {
            log_message('error', 'Account recovery: No staff found in MIS section.');
            return false;
        }

        $staffIds = array_column($sectionStaff, 'user_id');

        // -- 3. Build expertise maps & scores --
        $allUE = $this->userExpertiseModel->whereIn('user_id', $staffIds)->findAll();
        $userExpertiseMap = [];
        foreach ($allUE as $ue) {
            $userExpertiseMap[(int) $ue['user_id']][] = (int) $ue['expertise_id'];
        }

        $expertiseMatchScore = [];
        foreach ($matchedExpertise as $me) {
            $expertiseMatchScore[(int) $me['expertise_id']] = (int) $me['match_count'];
        }

        $scores = [];
        foreach ($sectionStaff as $staff) {
            $uid   = (int) $staff['user_id'];
            $score = 0;
            foreach (($userExpertiseMap[$uid] ?? []) as $eid) {
                if (isset($expertiseMatchScore[$eid])) {
                    $score += $expertiseMatchScore[$eid];
                }
            }
            $scores[$uid] = $score;
        }

        // -- 4. Count active tickets per candidate --
        $openId       = \App\Models\JobStatusModel::getIdByLabel('Open');
        $inProgressId = \App\Models\JobStatusModel::getIdByLabel('In Progress');
        $activeStatuses = [
            $openId,
            $inProgressId,
        ];
        $ticketCounts = [];
        foreach ($staffIds as $uid) {
            $uid = (int) $uid;
            $ticketCounts[$uid] = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $uid)
                ->whereIn('job_tickets.job_status', $activeStatuses)
                ->countAllResults();
        }

        // -- 5. Determine best candidate --
        $hasExpertiseMatch = !empty($scores) && max($scores) > 0;

        if ($hasExpertiseMatch) {
            $candidates = array_values(array_filter($staffIds, fn($uid) => $scores[(int) $uid] > 0));
            usort($candidates, function ($a, $b) use ($scores, $ticketCounts) {
                $a = (int) $a;
                $b = (int) $b;
                if ($scores[$a] !== $scores[$b]) {
                    return $scores[$b] <=> $scores[$a];
                }
                return $ticketCounts[$a] <=> $ticketCounts[$b];
            });
            $assignedStaffId = (int) $candidates[0];
        } else {
            // Fallback: section head (role_id=2), then any available staff
            $sectionHead = null;
            foreach ($sectionStaff as $staff) {
                if ((int) $staff['role_id'] === 2) {
                    $sectionHead = $staff;
                    break;
                }
            }
            if ($sectionHead) {
                $assignedStaffId = (int) $sectionHead['user_id'];
            } else {
                usort($staffIds, fn($a, $b) => $ticketCounts[(int) $a] <=> $ticketCounts[(int) $b]);
                $assignedStaffId = (int) $staffIds[0];
            }
        }

        // -- 6. Determine ticket status --
        if (!$hasExpertiseMatch) {
            $newStatus = $openId;
        } else {
            $hasInProgress = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $assignedStaffId)
                ->where('job_tickets.job_status', $inProgressId)
                ->countAllResults();
            $newStatus = $hasInProgress > 0
                ? $openId
                : $inProgressId;
        }

        // -- 7. Create job ticket --
        $this->jobTicketModel->insert(['job_status' => $newStatus]);
        $jobTicketId = $this->jobTicketModel->getInsertID();

        if (!$jobTicketId) {
            log_message('error', 'Account recovery: Failed to create job ticket for EmployeeNo: ' . ($employeeData['EmployeeNo'] ?? '?'));
            return false;
        }

        // -- 8. Create job ticket request --
        $this->jobTicketRequestModel->insert([
            'job_ticket_id'       => $jobTicketId,
            'section_id'          => $sectionId,
            'problem_description' => $problemDesc,
            'request_action'      => 2,
            'request_platform'    => 2,
            'request_type'        => 1,
        ]);

        // -- 9. Assign to selected staff --
        $this->jobTicketResponseModel->insert([
            'job_ticket_id' => $jobTicketId,
            'staff_id'      => $assignedStaffId,
            'start_date'    => date('Y-m-d'),
        ]);

        // -- 10. Notify assigned staff by email --
        $this->sendRecoveryAssignmentEmail($assignedStaffId, $jobTicketId, $sectionId, $problemDesc);

        // -- 11. Notify requestor by email (if alt_email exists) --
        $requester = $this->userModel->where('LOWER(email)', strtolower($emailStr))->first();
        if (!empty($requester['alt_email'])) {
            $this->notifyTicketCreated($requester['alt_email'], $jobTicketId, $requester['name'] ?? '', $section['name'] ?? 'MIS', $problemDesc, 'employee/my-tickets');
        }

        return true;
    }

    private function sendRecoveryAssignmentEmail(int $staffId, int $ticketId, int $sectionId, string $problemDesc): void
    {
        $staff = $this->userModel->find($staffId);
        if (!$staff || empty($staff['email'])) {
            log_message('warning', "Recovery ticket #{$ticketId}: assigned staff #{$staffId} has no email - skipping notification.");
            return;
        }

        $section     = $this->sectionModel->find($sectionId);
        $sectionName = $section['name'] ?? 'Unknown Section';

        $dashboardPath = match ((int) $staff['role_id']) {
            2       => 'admin/tickets',
            3       => 'ictu-staff/my-tickets',
            default => 'employee/my-tickets',
        };

        $emailBody = view('emails/ticket_assigned', [
            'staffName'   => $staff['name'],
            'ticketId'    => 'ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT),
            'sectionName' => $sectionName,
            'problem'     => $problemDesc,
            'ticketUrl'   => base_url($dashboardPath),
        ]);

        try {
            $email = \Config\Services::email();
            $email->setTo($staff['email']);
            $email->setSubject('New Ticket Assigned - ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT));
            $email->setMessage($emailBody);
            if (!$email->send()) {
                log_message('error', 'Recovery ticket assignment email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Recovery ticket assignment email exception: ' . $e->getMessage());
        }
    }

    private function notifyTicketCreated(string $toEmail, int $ticketId, string $userName, string $sectionName, string $problem, string $dashboardPath = 'employee/my-tickets'): void
    {
        if (empty($toEmail)) {
            return;
        }

        $formattedId = 'ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT);

        $emailBody = view('emails/ticket_submitted', [
            'requestorName' => $userName,
            'ticketId'      => $formattedId,
            'sectionName'   => $sectionName,
            'problem'       => $problem,
            'ticketUrl'     => base_url($dashboardPath),
        ]);

        try {
            $email = \Config\Services::email();
            $email->setTo($toEmail);
            $email->setSubject('Recovery Ticket Created - ' . $formattedId);
            $email->setMessage($emailBody);
            if (!$email->send()) {
                log_message('error', 'Recovery ticket confirmation email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Recovery ticket confirmation email exception: ' . $e->getMessage());
        }
    }

    public function accountRecovery()
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
        $lastName  = $this->request->getPost('last_name');
        $birthDate = $this->request->getPost('birth_date');

        if (empty($firstName) || empty($lastName) || empty($birthDate)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'First name, last name, and birthdate are required.'
                                  ]);
        }

        // Verify identity against API data
        if (mb_strtolower(trim($firstName)) !== mb_strtolower(trim($employeeData['FirstName']))
            || mb_strtolower(trim($lastName)) !== mb_strtolower(trim($employeeData['LastName']))
            || $birthDate !== $employeeData['DateOfBirth']) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'The first name, last name, or birthdate does not match our records.'
                                  ]);
        }

        $employeeEmail = $employeeData['EmailAddress'] ?? null;

        if (empty($employeeEmail)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON([
                                      'status'  => 'error',
                                      'message' => 'No email address found for this employee in our system.'
                                  ]);
        }

        // Check if employee exists in our users DB
        $user = $this->userModel->where('LOWER(email)', strtolower($employeeEmail))->first();

        if (empty($user)) {
            // Register the employee in our DB (Google OAuth account, no password)
            $fullName = trim(
                ($employeeData['FirstName'] ?? '') . ' '
                . ($employeeData['MiddleName'] ?? '') . ' '
                . ($employeeData['LastName'] ?? '')
            );
            $this->userModel->insert([
                'name'    => $fullName,
                'email'   => $employeeEmail,
                'role_id' => 4,
            ]);

            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON([
                                      'status'  => 'no_alt_email',
                                      'message' => 'Your account has been registered but no alternative email has been saved yet. Please visit the <strong>ICTU office</strong> to recover your account credentials.'
                                  ]);
        }

        // Employee is in the DB — check for alt_email
        if (!empty($user['alt_email'])) {
            $ticketCreated = $this->createAccountRecoveryTicket();
            if (!$ticketCreated) {
                return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                      ->setJSON([
                                          'status'  => 'error',
                                          'message' => 'Failed to create a recovery ticket. Please try again or visit the ICTU office.'
                                      ]);
            }

            $maskedAlt = $this->maskEmail($user['alt_email']);
            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON([
                                      'status'  => 'recovery_ticket_created',
                                      'message' => 'A recovery ticket has been created. Account recovery instructions will be sent to your alternative email: <span class="font-medium">' . $maskedAlt . '</span>.'
                                  ]);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                              ->setJSON([
                                  'status'  => 'no_alt_email',
                                  'message' => 'No alternative email has been saved for your account. Please visit the <strong>ICTU office</strong> to recover your account credentials.'
                              ]);
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
