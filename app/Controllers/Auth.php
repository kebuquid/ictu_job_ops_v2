<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use League\OAuth2\Client\Provider\Google;
use App\Models\UserModel;
use App\Models\RoleModel;

class Auth extends BaseController
{
    private UserModel $userModel;
    private string $authID;
    private string $apiToken;
    private string $validationEndpoint;

    public function __construct()
    {
        $this->authID             = env('UNISAP_AUTH_ID');
        $this->apiToken           = env('UNISAP_API_TOKEN');
        $this->validationEndpoint = (env('USE_TEST_API') === 'true') ? env('TEST_API_ENDPOINT') : env('UNISAP_API_ENDPOINT');
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

            // If no alt_email set, send to alt-email setup before dashboard
            if (empty($user['alt_email'])) {
                return redirect()->to(base_url('auth/alt-email-setup'));
            }

            // If the user arrived via a protected link (e.g. from an email button),
            // send them directly there instead of the generic dashboard.
            $loginRedirect = session()->get('login_redirect');
            if ($loginRedirect) {
                session()->remove('login_redirect');
                return redirect()->to($loginRedirect);
            }

            $roleData = (new RoleModel())->find((int) $user['role_id']);
            return redirect()->to(($roleData['url_path'] ?? '/student') . '/dashboard');

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
                    $userData['role_id'] = 4; // Employee role
                } else {
                    $userData['role_id'] = 5; // Student role
                }
                $userData['account_no'] = $this->checkUserViaApi($userData['email']);

                if($userData['account_no'] == null) {
                    return null;
                    log_message('warning', 'User with email ' . $userData['email'] . ' not found in external API during login.');
                }
                $this->userModel->insert($userData);
                $user['user_id'] = $this->userModel->getInsertID();
            }

            return $user['user_id'];
        }catch(\Exception $e) {
            log_message('error', 'Error checking user: ' . $e->getMessage());
            return null;
        }
    }

    public function checkUserViaApi($userEmail)
    {
        $client = service('curlrequest');
        if(str_ends_with($userEmail, '@cspc.edu.ph')){
            $response = $client->get($this->validationEndpoint . 'EmployeeInfoByEmail/' . $userEmail, [
                'headers' => [
                    'Auth-ID' => $this->authID,
                    'Authorization' => $this->apiToken
                ]
            ]);

            $arrayResponse = json_decode($response->getBody(), true);
            $accountNo = $arrayResponse['EmployeeInfo']['EmployeeNo'] 
            ?? null;
        } else {
            $response = $client->get($this->validationEndpoint . 'StudentInfoByEmail/' . $userEmail, [
                'headers' => [
                    'Auth-ID' => $this->authID,
                    'Authorization' => $this->apiToken
                ]
            ]);
            $arrayResponse = json_decode($response->getBody(), true);
            $accountNo = $arrayResponse['StudentInfo']['StudentNo'] ?? null;
        }
        log_message('info', 'API response for ' . $userEmail . ': ' . print_r($arrayResponse, true));

        if($arrayResponse['status'] == 404) {
            return null;
        }

        return $accountNo;
    }

    public function altEmailSetup()
    {
        // Must be logged in
        if (!session()->get('user')) {
            return redirect()->to('/login');
        }

        // If alt_email already set, go straight to dashboard
        $user = $this->userModel->find((int) session()->get('user')['user_id']);
        if (!empty($user['alt_email'])) {
            $roleData = (new RoleModel())->find((int) $user['role_id']);
            return redirect()->to(($roleData['url_path'] ?? '/employee') . '/dashboard');
        }

        return view('alt_email_setup');
    }

    public function sendAltEmailOtp()
    {
        if (!session()->get('user')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized.']);
        }

        $altEmail = strtolower(trim($this->request->getPost('alt_email')));

        if (empty($altEmail) || !filter_var($altEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Please enter a valid email address.']);
        }

        $currentUser = session()->get('user');
        $userId      = (int) $currentUser['user_id'];

        // Reject if the alt email is the same as the user's own primary email
        if (strtolower(trim($currentUser['email'])) === $altEmail) {
            return $this->response->setStatusCode(409)->setJSON(['status' => 'error', 'message' => 'Your recovery email cannot be the same as your primary email.']);
        }

        // Reject if the alt email is already used as a primary email by any user
        $primaryMatch = $this->userModel
            ->where('LOWER(email)', $altEmail)
            ->where('user_id !=', $userId)
            ->first();

        if ($primaryMatch) {
            return $this->response->setStatusCode(409)->setJSON(['status' => 'error', 'message' => 'This email is already registered as a primary account email.']);
        }

        // Reject if the alt email is already used as an alt email by another user
        $altMatch = $this->userModel
            ->where('LOWER(alt_email)', $altEmail)
            ->where('user_id !=', $userId)
            ->first();

        if ($altMatch) {
            return $this->response->setStatusCode(409)->setJSON(['status' => 'error', 'message' => 'This email is already linked as a recovery email on another account.']);
        }

        $otp     = (string) random_int(100000, 999999);
        $expires = time() + 600; // 10 minutes

        // ── Pre-flight: verify the email domain has MX/A records ──────────────
        $domain = substr($altEmail, strrpos($altEmail, '@') + 1);
        if (!checkdnsrr($domain, 'MX')) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'invalid_address',
                'message' => "The domain \"{$domain}\" is not set up to receive emails. Please check for typos (e.g. \"gamil.com\" instead of \"gmail.com\").",
            ]);
        }

        session()->set('alt_email_otp', [
            'email'   => $altEmail,
            'otp'     => password_hash($otp, PASSWORD_DEFAULT),
            'expires' => $expires,
        ]);

        $emailBody = view('emails/otp_verification', [
            'userName'      => session()->get('user')['name'],
            'otp'           => $otp,
            'expiryMinutes' => 10,
        ]);

        $email = \Config\Services::email();
        $email->setTo($altEmail);
        $email->setSubject('ICTU Job Ops — Account Recovery Email Verification');
        $email->setMessage($emailBody);
        $email->setMailType('html');

        if (!$email->send()) {
            $debugOutput = $email->printDebugger(['headers', 'subject', 'body']);
            log_message('error', 'Failed to send OTP email to ' . $altEmail . ': ' . $debugOutput);

            // ── Detect "address not found" type SMTP rejections ───────────────
            // Covers: 5xx delivery-failure codes (550-553, 511, 521, etc.),
            // enhanced status codes (#5.1.x), and common rejection phrases.
            $isAddressError = preg_match(
                '/\b5[012][0-9]\b|'                                    // 5xx (500-529) incl. 511, 521
                . '\b55[0-9]\b|'                                       // 550-559
                . '#5\.\d+\.\d+|'                                      // enhanced status: #5.1.1 etc.
                . 'no\s+mailbox\s+(here|found)|'                       // "no mailbox here by that name"
                . 'chkuser|'                                           // qmail chkuser plugin
                . 'address\s+not\s+found|user\s+unknown|no\s+such\s+user|'
                . 'mailbox\s+(not\s+found|unavailable|does\s+not\s+exist)|'
                . 'invalid\s+(address|recipient)|recipient\s+(not\s+found|rejected|unknown)|'
                . 'does\s+not\s+exist|undeliverable|couldn\'t\s+be\s+found|'
                . 'account\s+(does\s+not\s+exist|not\s+found)|address\s+rejected|'
                . 'bad\s+destination|Unknown\s+address\s+error|'
                . 'sorry,?\s+no\s+mailbox|that\s+name\s+does\s+not\s+exist/i',
                $debugOutput
            );

            if ($isAddressError) {
                session()->remove('alt_email_otp');
                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => 'invalid_address',
                    'message' => "The email address \"{$altEmail}\" could not be found or is unable to receive mail. Please double-check for typos and try again.",
                ]);
            }

            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
        }

        return $this->response->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'OTP sent to ' . $altEmail . '. Please check your inbox.']);
    }

    public function verifyAltEmailOtp()
    {
        if (!session()->get('user')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized.']);
        }

        $otp     = trim($this->request->getPost('otp'));
        $pending = session()->get('alt_email_otp');

        if (empty($pending)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'No OTP session found. Please request a new code.']);
        }

        if (time() > $pending['expires']) {
            session()->remove('alt_email_otp');
            return $this->response->setStatusCode(400)->setJSON(['status' => 'expired', 'message' => 'OTP has expired. Please request a new code.']);
        }

        if (!password_verify($otp, $pending['otp'])) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'Incorrect OTP. Please try again.']);
        }

        // Save alt_email
        $userId = (int) session()->get('user')['user_id'];
        $this->userModel->update($userId, ['alt_email' => $pending['email']]);
        session()->remove('alt_email_otp');

        // Refresh session user data
        $user = $this->userModel->getUserWithRole($userId);
        session()->set('user', $user);

        $roleData = (new RoleModel())->find((int) $user['role_id']);
        $dashboardUrl = ($roleData['url_path'] ?? '/employee') . '/dashboard';

        return $this->response->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Alternative email saved successfully.', 'redirect' => base_url($dashboardUrl)]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
