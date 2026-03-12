<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SectionModel;
use App\Models\BuildingModel;
use App\Models\OrganizationalUnitModel;
use App\Models\TicketEquipmentModel;
use App\Models\IssueTypeModel;
use App\Models\PriorityLevelModel;
use App\Models\RequestTypeModel;
use App\Models\RequestPlatformModel;
use App\Models\RequestActionModel;
use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\UserModel;
use App\Models\UserExpertiseModel;
use App\Models\ExpertiseModel;
use App\Models\ExpertiseSignalMapModel;
use App\Models\SectionRoleAccessModel;
use App\Models\FormOptionRoleAccessModel;
use App\Models\KeywordRuleModel;
use App\Models\TicketHistoryModel;
use App\Models\RoleModel;
use App\Models\JobStatusModel;

class TicketController extends BaseController
{
    private SectionModel $sectionModel;
    private BuildingModel $buildingModel;
    private OrganizationalUnitModel $orgUnitModel;
    private TicketEquipmentModel $equipmentModel;
    private IssueTypeModel $issueTypeModel;
    private PriorityLevelModel $priorityLevelModel;
    private RequestTypeModel $requestTypeModel;
    private RequestPlatformModel $requestPlatformModel;
    private RequestActionModel $requestActionModel;
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private UserModel $userModel;
    private UserExpertiseModel $userExpertiseModel;
    private ExpertiseModel $expertiseModel;
    private ExpertiseSignalMapModel $signalMapModel;
    private SectionRoleAccessModel $sectionRoleAccessModel;
    private FormOptionRoleAccessModel $formOptionRoleAccessModel;
    private KeywordRuleModel $keywordRuleModel;
    private TicketHistoryModel $ticketHistoryModel;

    public function __construct()
    {
        $this->sectionModel            = new SectionModel();
        $this->buildingModel           = new BuildingModel();
        $this->orgUnitModel            = new OrganizationalUnitModel();
        $this->equipmentModel          = new TicketEquipmentModel();
        $this->issueTypeModel          = new IssueTypeModel();
        $this->priorityLevelModel      = new PriorityLevelModel();
        $this->requestTypeModel        = new RequestTypeModel();
        $this->requestPlatformModel    = new RequestPlatformModel();
        $this->requestActionModel      = new RequestActionModel();
        $this->jobTicketModel          = new JobTicketModel();
        $this->jobTicketRequestModel   = new JobTicketRequestModel();
        $this->jobTicketResponseModel  = new JobTicketResponseModel();
        $this->userModel               = new UserModel();
        $this->userExpertiseModel      = new UserExpertiseModel();
        $this->expertiseModel          = new ExpertiseModel();
        $this->signalMapModel          = new ExpertiseSignalMapModel();
        $this->sectionRoleAccessModel  = new SectionRoleAccessModel();
        $this->formOptionRoleAccessModel = new FormOptionRoleAccessModel();
        $this->keywordRuleModel        = new KeywordRuleModel();
        $this->ticketHistoryModel      = new TicketHistoryModel();
    }

    /**
     * Determine the role-based prefix for the current user.
     */
    private function getRolePrefix(): string
    {
        $user     = session()->get('user');
        $roleId   = (int) ($user['role_id'] ?? 5);
        $roleData = (new RoleModel())->find($roleId);
        return ltrim($roleData['url_path'] ?? '/employee', '/');
    }

    /**
     * Display the Intelligent Dispatcher ticket form
     */
    public function create()
    {
        $user       = session()->get('user');
        $roleId     = (int) ($user['role_id'] ?? 5);
        $rolePrefix = $this->getRolePrefix();

        // Fetch only sections this role is allowed to access
        $allowedIds = $this->sectionRoleAccessModel->getEnabledSectionIds($roleId);

        if (empty($allowedIds)) {
            // No sections enabled — show error on dashboard
            return redirect()->to($rolePrefix . '/dashboard')
                             ->with('error', 'No ticket sections are currently available for your role. Please contact the administrator.');
        }

        $sections = $this->sectionModel->whereIn('section_id', $allowedIds)
                                       ->orderBy('acronym', 'ASC')
                                       ->findAll();

        // Build list of allowed acronyms for JS keyword-rule filtering
        $allowedAcronyms = array_map(fn($s) => strtoupper($s['acronym']), $sections);

        // Fetch dynamic keyword rules from database (only for allowed sections)
        $keywordRulesData = $this->keywordRuleModel->getGroupedRulesForForm($allowedIds);

        $orgUnits       = $this->orgUnitModel
                            ->select('organizational_units.*, buildings.name as building_name')
                            ->join('buildings', 'buildings.building_id = organizational_units.building_id', 'left')
                            ->orderBy('organizational_units.name', 'ASC')
                            ->findAll();
        $buildings       = $this->buildingModel->orderBy('name', 'ASC')->findAll();
        $priorityLevels  = $this->priorityLevelModel->findAll();

        $viewName = $rolePrefix === 'student'
            ? 'students/create_ticket'
            : 'employees/create_ticket';

        return view($viewName, [
            'sections'           => $sections,
            'orgUnits'           => $orgUnits,
            'buildings'          => $buildings,
            'priorityLevels'     => $priorityLevels,
            'user'               => $user,
            'rolePrefix'         => $rolePrefix,
            'allowedAcronyms'    => $allowedAcronyms,
            'keywordRulesData'   => $keywordRulesData,
        ]);
    }

    /**
     * AJAX â€“ return section-specific form data (equipment, issue types, request types, etc.)
     */
    public function getSectionData(int $sectionId)
    {
        // Determine current user's role for access filtering
        $user   = session()->get('user');
        $roleId = (int) ($user['role_id'] ?? 5);
        $equipment    = $this->equipmentModel->where('section_id', $sectionId)->findAll();
        $issueTypes  = $this->issueTypeModel->where('section_id', $sectionId)->findAll();
        $requestTypes = $this->requestTypeModel->where('section_id', $sectionId)->findAll();
        $actions      = $this->requestActionModel->where('section_id', $sectionId)->findAll();

        // Get platforms for each request type in this section
        $platforms = [];
        foreach ($requestTypes as $rt) {
            $rtPlatforms = $this->requestPlatformModel
                ->where('request_type_id', $rt['request_type_id'])
                ->findAll();
            $platforms = array_merge($platforms, $rtPlatforms);
        }

        // ── Filter by form-option role access (Employee / Student) ──
        if (in_array($roleId, [4, 5], true)) {
            $allowedEquipment  = $this->formOptionRoleAccessModel->getEnabledOptionIds('equipment', $roleId);
            $allowedTypes      = $this->formOptionRoleAccessModel->getEnabledOptionIds('request_type', $roleId);
            $allowedPlatforms  = $this->formOptionRoleAccessModel->getEnabledOptionIds('request_platform', $roleId);
            $allowedActions    = $this->formOptionRoleAccessModel->getEnabledOptionIds('request_action', $roleId);

            $equipment    = array_filter($equipment,    fn($e)  => in_array((int) $e['equipment_id'], $allowedEquipment, true));
            $requestTypes = array_filter($requestTypes, fn($rt) => in_array((int) $rt['request_type_id'], $allowedTypes, true));
            $platforms    = array_filter($platforms,     fn($p)  => in_array((int) $p['platform_id'], $allowedPlatforms, true));
            $actions      = array_filter($actions,       fn($a)  => in_array((int) $a['action_id'], $allowedActions, true));
        }

        // Separate issue types by domain
        $hardwareIssues = array_filter($issueTypes, fn($i) => ($i['issue_type_domain'] ?? '') === 'Hardware');
        $softwareIssues = array_filter($issueTypes, fn($i) => ($i['issue_type_domain'] ?? '') === 'Software');

        return $this->response->setJSON([
            'equipment'       => array_values($equipment),
            'issue_types'     => array_values($issueTypes),
            'hardware_issues' => array_values($hardwareIssues),
            'software_issues' => array_values($softwareIssues),
            'request_types'   => array_values($requestTypes),
            'request_actions' => array_values($actions),
            'platforms'       => array_values($platforms),
        ]);
    }

    /**
     * AJAX â€“ get platforms and actions for a specific request type (MIS dependent dropdowns)
     */
    public function getRequestTypeData(int $requestTypeId)
    {
        $platforms = $this->requestPlatformModel->where('request_type_id', $requestTypeId)->findAll();
        $actions   = $this->requestActionModel->where('request_type_id', $requestTypeId)->findAll();

        // Filter by form-option role access (Employee / Student)
        $user   = session()->get('user');
        $roleId = (int) ($user['role_id'] ?? 5);

        if (in_array($roleId, [4, 5], true)) {
            $allowedPlatforms = $this->formOptionRoleAccessModel->getEnabledOptionIds('request_platform', $roleId);
            $allowedActions   = $this->formOptionRoleAccessModel->getEnabledOptionIds('request_action', $roleId);

            $platforms = array_filter($platforms, fn($p) => in_array((int) $p['platform_id'], $allowedPlatforms, true));
            $actions   = array_filter($actions,   fn($a) => in_array((int) $a['action_id'], $allowedActions, true));
        }

        return $this->response->setJSON([
            'platforms' => array_values($platforms),
            'actions'   => array_values($actions),
        ]);
    }

    /**
     * Handle ticket submission
     */
    public function store()
    {
        // â”€â”€ Validate common required fields â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $rules = [
            'office_id'           => 'required',
            'section_id'          => 'required|integer',
            'problem_description' => 'required',
        ];

        // Validate file if provided
        $file = $this->request->getFile('equipment_photo');
        if ($file && $file->isValid()) {
            $rules['equipment_photo'] = 'uploaded[equipment_photo]|max_size[equipment_photo,10240]|is_image[equipment_photo]|mime_in[equipment_photo,image/jpg,image/jpeg,image/png,image/gif,image/webp]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        // ── Verify section access for this role ──────────
        $user = session()->get('user');
        $roleId = (int) ($user['role_id'] ?? 5);
        $sectionId = (int) $this->request->getPost('section_id');
        $allowedIds = $this->sectionRoleAccessModel->getEnabledSectionIds($roleId);

        if (!in_array($sectionId, $allowedIds, true)) {
            return redirect()->back()->withInput()->with('error', 'You do not have access to submit tickets for this section.');
        }

        // ── Handle file upload ───────────────────────────
        $filePath = null;
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'tickets';

            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Unique name: random + original extension
            $newName  = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $filePath = 'uploads/tickets/' . $newName;
        }

        // â”€â”€ 1) Insert into job_tickets (parent) â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $user = session()->get('user');
        $requestorId = $user['user_id'] ?? null;

        $openId = JobStatusModel::getIdByLabel('Open');

        $ticketId = $this->jobTicketModel->insert([
            'requestor_id'   => $requestorId,
            'job_status'     => $openId,
        ]);

        if (! $ticketId) {
            return redirect()->back()->withInput()->with('error', 'Failed to create ticket. Please try again.');
        }

        // Log: ticket created
        $this->ticketHistoryModel->log($ticketId, 'created', null, $openId, (int) $requestorId, 'Ticket submitted');

        // â”€â”€ 2) Insert into job_ticket_requests (details) â”€
        $sectionId = (int) $this->request->getPost('section_id');

        // Hardware/software issues: arrays â†’ comma-separated string
        $hwIssues = $this->request->getPost('hardware_issues');
        $swIssues = $this->request->getPost('software_issues');

        $requestData = [
            'job_ticket_id'          => $ticketId,
            'section_id'             => $sectionId,
            'problem_description'    => $this->request->getPost('problem_description'),
            'requestor_no'           => $this->request->getPost('requestor_number'),
            'requestor_office'       => $this->request->getPost('office_id'),
            'request_type'           => $this->request->getPost('request_type') ?? $this->request->getPost('request_type_id'),
            'request_platform'       => $this->request->getPost('request_platform_id'),
            'request_equipment'      => $this->request->getPost('equipment'),
            'request_action'         => $this->request->getPost('action') ?? $this->request->getPost('request_action_id'),
            'equipment_location'     => $this->request->getPost('building_id'),
            'priority_level'         => $this->request->getPost('priority_level_id'),
            'hardware_issues'        => $hwIssues ? implode(',', $hwIssues) : null,
            'sofware_issues'         => $swIssues ? implode(',', $swIssues) : null,
            'brand_model'            => trim(($this->request->getPost('brand') ?? '') . ' ' . ($this->request->getPost('model') ?? '')) ?: null,
            'additional_details'     => $this->request->getPost('additional_details'),
            'additional_request_file' => $filePath,
        ];

        if (! $this->jobTicketRequestModel->insert($requestData)) {
            return redirect()->back()->withInput()->with('error', 'Ticket created but request details failed to save.');
        }

        // â”€â”€ 3) Auto-assign to best-matching staff â”€â”€â”€â”€â”€
        $assignment = $this->autoAssignTicket($ticketId, $sectionId, $requestData);

        // â"€â"€ 4) Send email notification to assigned employee â"€â"€â"€â"€â"€
        if ($assignment) {
            $staffName  = $this->userModel->find($assignment['staff_id'])['name'] ?? 'Unknown';
            $newStatus  = $assignment['status'];

            $inProgressId = JobStatusModel::getIdByLabel('In Progress');

            // Log: assigned
            $this->ticketHistoryModel->log(
                $ticketId,
                'assigned',
                $openId,
                $newStatus,
                null,
                'Auto-assigned to ' . $staffName
            );

            // If the ticket was set to In Progress, also log the status change
            if ($newStatus === $inProgressId) {
                $this->ticketHistoryModel->log(
                    $ticketId,
                    'in_progress',
                    $openId,
                    $inProgressId,
                    null,
                    'Automatically set to In Progress (staff has no other active ticket)'
                );
            }

            $this->sendAssignmentEmail($assignment['staff_id'], $ticketId, $requestData);

            // Notify the requestor if their ticket is immediately set to In Progress
            if ($newStatus === $inProgressId) {
                $this->notifyTicketStatusUpdate(
                    $ticketId,
                    'in_progress',
                    'In Progress',
                    'Your ticket has been assigned and a technician is already working on it.'
                );
            }
        }

        // ── 5) Send confirmation email to the requestor ──
        $this->notifyTicketCreated($user, $ticketId, $sectionId, $requestData);

        $prefix = $this->getRolePrefix();
        return redirect()->to($prefix . '/create-ticket')->with('success', 'Your ticket has been submitted successfully!');
    }

    /**
     * Auto-assign a ticket to the best-matching employee using deterministic
     * signal-based expertise matching.
     *
     * Algorithm:
     *  1. Extract ticket signals (equipment, request_type, platform, action, issue_types)
     *  2. Look up expertise_signal_map for matching expertise_ids
     *  3. Find employees in the section that have those expertise
     *  4. Rank by: (a) signal match count DESC, (b) fewest active tickets ASC
     *  5. If NO expertise match at all â†’ fall back to section head (role = ADMIN)
     *
     * @return array{staff_id: int, status: int}|null  The assigned staff user_id and ticket status, or null.
     */
    private function autoAssignTicket(int $ticketId, int $sectionId, array $requestData): ?array
    {
        // -- 1. Collect ticket signals --
        $signals = [
            'equipment'    => [],
            'request_type' => [],
            'platform'     => [],
            'action'       => [],
            'issue_type'   => [],
        ];

        if (! empty($requestData['request_equipment'])) {
            $signals['equipment'][] = (int) $requestData['request_equipment'];
        }
        if (! empty($requestData['request_type'])) {
            $signals['request_type'][] = (int) $requestData['request_type'];
        }
        if (! empty($requestData['request_platform'])) {
            $signals['platform'][] = (int) $requestData['request_platform'];
        }
        if (! empty($requestData['request_action'])) {
            $signals['action'][] = (int) $requestData['request_action'];
        }
        // Hardware + software issues are comma-separated IDs
        if (! empty($requestData['hardware_issues'])) {
            foreach (explode(',', $requestData['hardware_issues']) as $id) {
                $signals['issue_type'][] = (int) trim($id);
            }
        }
        if (! empty($requestData['sofware_issues'])) {
            foreach (explode(',', $requestData['sofware_issues']) as $id) {
                $signals['issue_type'][] = (int) trim($id);
            }
        }

        // -- 2. Expertise match via signal map --
        $matchedExpertise = $this->signalMapModel->findMatchingExpertise($signals);

        // -- 3. Get all section staff (admin + ictu staff) --
        $sectionStaff = $this->userModel
            ->whereIn('role_id', [
                2,
                3,
            ])
            ->where('section_id', $sectionId)
            ->findAll();

        if (empty($sectionStaff)) {
            return null;
        }

        $staffIds = array_column($sectionStaff, 'user_id');

        // -- 4. Get user_expertise for candidates --
        $allUE = $this->userExpertiseModel->whereIn('user_id', $staffIds)->findAll();
        $userExpertiseMap = [];
        foreach ($allUE as $ue) {
            $userExpertiseMap[(int) $ue['user_id']][] = (int) $ue['expertise_id'];
        }

        // -- 5. Score each candidate by expertise signal match --
        $expertiseMatchScore = [];
        foreach ($matchedExpertise as $me) {
            $expertiseMatchScore[(int) $me['expertise_id']] = (int) $me['match_count'];
        }

        $scores = [];
        foreach ($sectionStaff as $staff) {
            $uid = (int) $staff['user_id'];
            $score = 0;
            foreach (($userExpertiseMap[$uid] ?? []) as $eid) {
                if (isset($expertiseMatchScore[$eid])) {
                    $score += $expertiseMatchScore[$eid];
                }
            }
            $scores[$uid] = $score;
        }

        // -- 6. Count active tickets per candidate --
        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
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

        // -- 7. Determine best candidate --
        $hasExpertiseMatch = ! empty($scores) && max($scores) > 0;

        if ($hasExpertiseMatch) {
            $candidates = array_filter($staffIds, fn($uid) => $scores[(int) $uid] > 0);
            $candidates = array_values($candidates);

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
            // Fallback: assign to section head (ADMIN role)
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

        // -- 8. Determine ticket status based on staff workload --
        // If no expertise match (section head fallback) → always OPEN
        // If staff already has an IN_PROGRESS ticket → OPEN (queued)
        // Otherwise → IN_PROGRESS
        if (! $hasExpertiseMatch) {
            // Escalated to section head – keep as OPEN
            $newStatus = $openId;
        } else {
            // Check if the assigned staff already has a ticket IN_PROGRESS
            $hasInProgress = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $assignedStaffId)
                ->where('job_tickets.job_status', $inProgressId)
                ->countAllResults();

            $newStatus = $hasInProgress > 0
                ? $openId
                : $inProgressId;
        }

        // -- 9. Create response row & update status --
        $this->jobTicketResponseModel->insert([
            'job_ticket_id' => $ticketId,
            'staff_id'      => $assignedStaffId,
            'start_date'    => date('Y-m-d'),
        ]);

        $this->jobTicketModel->update($ticketId, [
            'job_status' => $newStatus,
        ]);

        return ['staff_id' => $assignedStaffId, 'status' => $newStatus];
    }

    /**
     * Send a confirmation email to the requestor after ticket submission.
     */
    private function notifyTicketCreated(array $user, int $ticketId, int $sectionId, array $requestData): void
    {
        $toEmail = $user['email'] ?? '';
        if (empty($toEmail)) {
            return;
        }

        $section     = $this->sectionModel->find($sectionId);
        $sectionName = $section['name'] ?? 'Unknown Section';
        $formattedId = 'ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT);

        $dashboardPath = match ((int) ($user['role_id'] ?? 5)) {
            2       => 'admin/tickets',
            3       => 'ictu-staff/my-tickets',
            4       => 'employee/my-tickets',
            5       => 'student/my-tickets',
            default => 'employee/my-tickets',
        };

        $emailBody = view('emails/ticket_submitted', [
            'requestorName' => $user['name'] ?? 'User',
            'ticketId'      => $formattedId,
            'sectionName'   => $sectionName,
            'problem'       => $requestData['problem_description'] ?? 'N/A',
            'ticketUrl'     => base_url($dashboardPath),
        ]);

        try {
            $email = \Config\Services::email();
            $email->setTo($toEmail);
            $email->setSubject('Ticket Submitted - ' . $formattedId);
            $email->setMessage($emailBody);
            if (! $email->send()) {
                log_message('error', 'Ticket confirmation email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Ticket confirmation email exception: ' . $e->getMessage());
        }
    }

    /**
     * Send an update email to the ticket requestor when the ticket status changes.
     *
     * @param int    $ticketId   The ID of the ticket.
     * @param string $status     Machine-readable status slug (e.g. 'in_progress', 'completed', 'closed').
     * @param string $statusLabel Human-readable status label (e.g. 'In Progress').
     * @param string|null $updateNote Optional note to include in the email body.
     */
    private function notifyTicketStatusUpdate(int $ticketId, string $status, string $statusLabel, ?string $updateNote = null): void
    {
        $ticket = $this->jobTicketModel
            ->select('job_tickets.job_ticket_id, job_tickets.requestor_id, job_ticket_requests.problem_description, job_ticket_requests.section_id, users.email, users.alt_email, users.name as requestor_name, users.role_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users', 'users.user_id = job_tickets.requestor_id', 'left')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->first();

        if (! $ticket || empty($ticket['email'])) {
            log_message('warning', "Ticket #{$ticketId}: requestor has no email - skipping status update notification.");
            return;
        }

        // Google account recovery tickets must be sent to the alt_email
        $isRecovery = str_contains($ticket['problem_description'] ?? '', 'Google Account recovery');
        $toEmail    = ($isRecovery && ! empty($ticket['alt_email'])) ? $ticket['alt_email'] : $ticket['email'];

        $section     = $this->sectionModel->find($ticket['section_id']);
        $sectionName = $section['name'] ?? 'Unknown Section';
        $formattedId = 'ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT);

        $dashboardPath = match ((int) ($ticket['role_id'] ?? 5)) {
            2       => 'admin/tickets',
            3       => 'ictu-staff/my-tickets',
            4       => 'employee/my-tickets',
            5       => 'student/my-tickets',
            default => 'employee/my-tickets',
        };

        $emailBody = view('emails/ticket_update', [
            'requestorName' => $ticket['requestor_name'],
            'ticketId'      => $formattedId,
            'sectionName'   => $sectionName,
            'problem'       => $ticket['problem_description'] ?? 'N/A',
            'status'        => $status,
            'statusLabel'   => $statusLabel,
            'updateNote'    => $updateNote,
            'ticketUrl'     => base_url($dashboardPath),
        ]);

        try {
            $email = \Config\Services::email();
            $email->setTo($toEmail);
            $email->setSubject('Ticket Update: ' . $statusLabel . ' - ' . $formattedId);
            $email->setMessage($emailBody);
            if (! $email->send()) {
                log_message('error', 'Ticket status update email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Ticket status update email exception: ' . $e->getMessage());
        }
    }

    /**
     * Send an email notification to the assigned employee.
     */
    private function sendAssignmentEmail(int $staffId, int $ticketId, array $requestData): void
    {
        $staff = $this->userModel->find($staffId);

        if (! $staff || empty($staff['email'])) {
            log_message('warning', "Ticket #{$ticketId}: assigned staff #{$staffId} has no email - skipping notification.");
            return;
        }

        // Look up the section name
        $section = $this->sectionModel->find($requestData['section_id'] ?? 0);
        $sectionName = $section['name'] ?? 'Unknown Section';

        // Build ticket URL (for the assigned employee's dashboard)
        $dashboardPath = match ((int) $staff['role_id']) {
            2       => 'admin/tickets',
            3       => 'ictu-staff/my-tickets',
            4       => 'employee/my-tickets',
            5       => 'student/my-tickets',
            default => 'employee/my-tickets',
        };
        $ticketUrl = base_url($dashboardPath);

        // Render HTML email body from a view
        $emailBody = view('emails/ticket_assigned', [
            'staffName'   => $staff['name'],
            'ticketId'    => 'ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT),
            'sectionName' => $sectionName,
            'problem'     => $requestData['problem_description'] ?? 'N/A',
            'ticketUrl'   => $ticketUrl,
        ]);

        try {
            $email = \Config\Services::email();
            $email->setTo($staff['email']);
            $email->setSubject('New Ticket Assigned - ICTU-' . date('Y') . '-' . str_pad($ticketId, 5, '0', STR_PAD_LEFT));
            $email->setMessage($emailBody);

            if (! $email->send()) {
                log_message('error', 'Ticket assignment email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Ticket assignment email exception: ' . $e->getMessage());
        }
    }
}
