<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\SectionModel;
use App\Models\BuildingModel;
use App\Models\ExpertiseModel;
use App\Models\UserExpertiseModel;
use App\Models\IssueTypeModel;
use App\Models\OrganizationalUnitModel;
use App\Models\PriorityLevelModel;
use App\Models\RequestActionModel;
use App\Models\RequestPlatformModel;
use App\Models\RequestTypeModel;
use App\Models\TicketEquipmentModel;
use App\Models\SectionRoleAccessModel;
use App\Models\FormOptionRoleAccessModel;
use App\Models\KeywordRuleModel;
use App\Models\TicketSlaRuleModel;

use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\ResponsePartModel;
use App\Models\TicketHistoryModel;
use App\Models\RoleModel;

use App\Models\JobStatusModel;
use App\Libraries\TicketSlaResolver;

class SuperAdminController extends BaseController
{
    private UserModel $userModel;
    private SectionModel $sectionModel;
    private BuildingModel $buildingModel;
    private ExpertiseModel $expertiseModel;
    private UserExpertiseModel $userExpertiseModel;
    private IssueTypeModel $issueTypeModel;
    private OrganizationalUnitModel $orgUnitModel;
    private PriorityLevelModel $priorityLevelModel;
    private RequestActionModel $requestActionModel;
    private RequestPlatformModel $requestPlatformModel;
    private RequestTypeModel $requestTypeModel;
    private TicketEquipmentModel $ticketEquipmentModel;
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private ResponsePartModel $responsePartModel;
    private TicketHistoryModel $ticketHistoryModel;
    private SectionRoleAccessModel $sectionRoleAccessModel;
    private FormOptionRoleAccessModel $formOptionRoleAccessModel;
    private KeywordRuleModel $keywordRuleModel;
    private TicketSlaRuleModel $ticketSlaRuleModel;
    private TicketSlaResolver $ticketSlaResolver;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->sectionModel = new SectionModel();
        $this->buildingModel = new BuildingModel();
        $this->expertiseModel = new ExpertiseModel();
        $this->userExpertiseModel = new UserExpertiseModel();
        $this->issueTypeModel = new IssueTypeModel();
        $this->orgUnitModel = new OrganizationalUnitModel();
        $this->priorityLevelModel = new PriorityLevelModel();
        $this->requestActionModel = new RequestActionModel();
        $this->requestPlatformModel = new RequestPlatformModel();
        $this->requestTypeModel = new RequestTypeModel();
        $this->ticketEquipmentModel = new TicketEquipmentModel();
        $this->jobTicketModel = new JobTicketModel();
        $this->jobTicketRequestModel = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->responsePartModel = new ResponsePartModel();
        $this->ticketHistoryModel = new TicketHistoryModel();
        $this->sectionRoleAccessModel = new SectionRoleAccessModel();
        $this->formOptionRoleAccessModel = new FormOptionRoleAccessModel();
        $this->keywordRuleModel = new KeywordRuleModel();
        $this->ticketSlaRuleModel = new TicketSlaRuleModel();
        $this->ticketSlaResolver = new TicketSlaResolver();
    }

    public function dashboard()
    {
        $user = session()->get('user');

        // ── 1. System-wide ticket stats by status ──
        $statusRows = $this->jobTicketModel
            ->select('job_status, COUNT(*) as cnt')
            ->groupBy('job_status')
            ->findAll();

        $stats = [
            'total'       => 0,
            'open'        => 0,
            'in_progress' => 0,
            'completed'   => 0,
            'closed'      => 0,
            'cancelled'   => 0,
        ];

        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
        $completedId  = JobStatusModel::getIdByLabel('Completed');
        $closedId     = JobStatusModel::getIdByLabel('Closed');
        $cancelledId  = JobStatusModel::getIdByLabel('Cancelled');

        foreach ($statusRows as $r) {
            $stats['total'] += (int) $r['cnt'];
            match ((int) $r['job_status']) {
                $openId       => $stats['open']        += (int) $r['cnt'],
                $inProgressId => $stats['in_progress'] += (int) $r['cnt'],
                $completedId  => $stats['completed']   += (int) $r['cnt'],
                $closedId     => $stats['closed']      += (int) $r['cnt'],
                $cancelledId  => $stats['cancelled']   += (int) $r['cnt'],
                default => null,
            };
        }

        // ── 2. Active users (employees with assigned roles 1-5) ──
        $activeUsers = $this->userModel->where('role_id <', 6)->countAllResults();

        // Total registered users
        $totalUsers = $this->userModel->countAllResults();

        // ── 3. Tickets per section ──
        $sectionStats = $this->jobTicketModel
            ->select('sections.acronym as section_name, sections.section_id, COUNT(*) as cnt')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('sections', 'sections.section_id = job_ticket_requests.section_id')
            ->groupBy('job_ticket_requests.section_id')
            ->orderBy('cnt', 'DESC')
            ->findAll();

        // ── 4. Recent tickets (last 8) with requestor + priority + response ──
        $recentTickets = $this->jobTicketModel
            ->select('job_tickets.*, 
                      job_ticket_requests.problem_description, 
                      job_ticket_requests.priority_level,
                      priority_levels.priority_name,
                      users.name as requestor_name,
                      sections.acronym as section_acronym,
                      resp_staff.name as staff_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users', 'users.user_id = job_tickets.requestor_id', 'left')
            ->join('priority_levels', 'priority_levels.priority_level_id = job_ticket_requests.priority_level', 'left')
            ->join('sections', 'sections.section_id = job_ticket_requests.section_id', 'left')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users as resp_staff', 'resp_staff.user_id = job_ticket_responses.staff_id', 'left')
            ->orderBy('job_tickets.created_at', 'DESC')
            ->limit(8)
            ->findAll();

        // ── 5. Top technicians by resolved tickets ──
        $topTechnicians = $this->jobTicketResponseModel
            ->select('users.user_id, users.name, users.avatar, COUNT(*) as resolved_count')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id')
            ->whereIn('job_tickets.job_status', [$completedId, $closedId])
            ->groupBy('job_ticket_responses.staff_id')
            ->orderBy('resolved_count', 'DESC')
            ->limit(5)
            ->findAll();

        // Find max resolved for progress bar scaling
        $maxResolved = 0;
        foreach ($topTechnicians as &$tech) {
            $tech['initials'] = $this->userModel->get_initials($tech['name']);
            if ((int) $tech['resolved_count'] > $maxResolved) {
                $maxResolved = (int) $tech['resolved_count'];
            }
        }

        // ── 6. Recent activity (last 6 created/updated tickets) ──
        $recentActivity = $this->jobTicketModel
            ->select('job_tickets.*, 
                      job_ticket_requests.problem_description,
                      users.name as requestor_name,
                      resp_staff.name as staff_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users', 'users.user_id = job_tickets.requestor_id', 'left')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users as resp_staff', 'resp_staff.user_id = job_ticket_responses.staff_id', 'left')
            ->orderBy('job_tickets.updated_at', 'DESC')
            ->limit(6)
            ->findAll();

        // ── 7. Pending verification count ──
        $pendingVerification = $this->jobTicketResponseModel
            ->where('completion_status IS NOT NULL')
            ->where('verifier_id IS NULL')
            ->countAllResults();

        return view('super_admin/dashboard', [
            'user'                => $user,
            'stats'               => $stats,
            'activeUsers'         => $activeUsers,
            'totalUsers'          => $totalUsers,
            'sectionStats'        => $sectionStats,
            'recentTickets'       => $recentTickets,
            'topTechnicians'      => $topTechnicians,
            'maxResolved'         => $maxResolved,
            'recentActivity'      => $recentActivity,
            'pendingVerification' => $pendingVerification,
        ]);
    }

    /**
     * All Tickets page – lists every ticket with filters by job status.
     */
    public function tickets()
    {
        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
        $completedId  = JobStatusModel::getIdByLabel('Completed');
        $closedId     = JobStatusModel::getIdByLabel('Closed');
        $cancelledId  = JobStatusModel::getIdByLabel('Cancelled');

        $tickets = $this->jobTicketModel
            ->select('job_tickets.*,
                      job_ticket_requests.problem_description,
                      job_ticket_requests.priority_level,
                      job_ticket_requests.section_id,
                      priority_levels.priority_name,
                      users.name as requestor_name,
                      sections.acronym as section_acronym,
                      sections.name as section_name,
                      resp_staff.name as staff_name,
                      job_ticket_responses.job_ticket_response_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users', 'users.user_id = job_tickets.requestor_id', 'left')
            ->join('priority_levels', 'priority_levels.priority_level_id = job_ticket_requests.priority_level', 'left')
            ->join('sections', 'sections.section_id = job_ticket_requests.section_id', 'left')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users as resp_staff', 'resp_staff.user_id = job_ticket_responses.staff_id', 'left')
            ->orderBy('job_tickets.created_at', 'DESC')
            ->findAll();

        // Status counts for filter badges
        $statusCounts = [
            'all'         => count($tickets),
            'open'        => 0,
            'in_progress' => 0,
            'completed'   => 0,
            'closed'      => 0,
            'cancelled'   => 0,
        ];
        foreach ($tickets as $t) {
            match ((int) $t['job_status']) {
                $openId       => $statusCounts['open']++,
                $inProgressId => $statusCounts['in_progress']++,
                $completedId  => $statusCounts['completed']++,
                $closedId     => $statusCounts['closed']++,
                $cancelledId  => $statusCounts['cancelled']++,
                default => null,
            };
        }

        return view('super_admin/tickets', [
            'tickets'      => $tickets,
            'statusCounts' => $statusCounts,
        ]);
    }

    /**
     * View a single ticket with full details and history.
     */
    public function viewTicket(int $ticketId)
    {
        $ticket = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.*')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->first();

        if (! $ticket) {
            return redirect()->to('super-admin/tickets')->with('error', 'Ticket not found.');
        }

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, users.name as staff_name')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_id', $ticketId)
            ->first();

        $slaSummary = $this->ticketSlaResolver->resolveForTicket($ticket, $response);

        return view('super_admin/view_ticket', [
            'ticket'        => $ticket,
            'response'      => $response,
            'responseParts' => $response ? $this->responsePartModel->getByResponseId((int) $response['job_ticket_response_id']) : [],
            'history'       => $this->ticketHistoryModel->getByTicketId($ticketId),
            'slaSummary'    => $slaSummary,
        ]);
    }

    public function ticketSlaRules()
    {
        $rules = $this->ticketSlaRuleModel
            ->select('ticket_sla_rules.*, sections.acronym, request_types.request_type_name, request_platforms.platform_name, request_actions.action_name, ticket_equipments.name as equipment_name')
            ->join('sections', 'sections.section_id = ticket_sla_rules.section_id')
            ->join('request_types', 'request_types.request_type_id = ticket_sla_rules.request_type_id', 'left')
            ->join('request_platforms', 'request_platforms.platform_id = ticket_sla_rules.platform_id', 'left')
            ->join('request_actions', 'request_actions.action_id = ticket_sla_rules.action_id', 'left')
            ->join('ticket_equipments', 'ticket_equipments.equipment_id = ticket_sla_rules.equipment_id', 'left')
            ->orderBy('sections.acronym', 'ASC')
            ->orderBy('ticket_sla_rules.target_hours', 'ASC')
            ->findAll();

        return view('super_admin/ticket_sla_rules', [
            'rules'        => $rules,
            'sections'     => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
            'requestTypes' => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
            'platforms'    => $this->requestPlatformModel->orderBy('platform_name', 'ASC')->findAll(),
            'actions'      => $this->requestActionModel->orderBy('action_name', 'ASC')->findAll(),
            'equipments'   => $this->ticketEquipmentModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function addTicketSlaRule()
    {
        $rules = [
            'section_id'    => 'required|integer',
            'request_type_id' => 'permit_empty|integer',
            'platform_id'   => 'permit_empty|integer',
            'action_id'     => 'permit_empty|integer',
            'equipment_id'  => 'permit_empty|integer',
            'target_hours'  => 'required|integer|greater_than[0]',
            'notes'         => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('super-admin/ticket-sla-rules')->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $this->ticketSlaRuleModel->insert([
            'section_id'      => (int) $this->request->getPost('section_id'),
            'request_type_id' => $this->request->getPost('request_type_id') ?: null,
            'platform_id'     => $this->request->getPost('platform_id') ?: null,
            'action_id'       => $this->request->getPost('action_id') ?: null,
            'equipment_id'    => $this->request->getPost('equipment_id') ?: null,
            'target_hours'    => (int) $this->request->getPost('target_hours'),
            'is_active'       => 1,
            'notes'           => trim((string) $this->request->getPost('notes')) ?: null,
        ]);

        return redirect()->to('super-admin/ticket-sla-rules')->with('success', 'Ticket timeframe rule added successfully.');
    }

    public function deleteTicketSlaRule(int $id)
    {
        $item = $this->ticketSlaRuleModel->find($id);
        if (! $item) {
            return redirect()->to('super-admin/ticket-sla-rules')->with('error', 'Timeframe rule not found.');
        }

        $this->ticketSlaRuleModel->delete($id);
        return redirect()->to('super-admin/ticket-sla-rules')->with('success', 'Ticket timeframe rule deleted.');
    }

    public function employees()
    {
        return view('super_admin/employees', [
            'employees' => $this->userModel->getEmployees()
        ]);
    }

    public function addEmployeePage()
    {
        $sections = $this->sectionModel->findAll();

        return view('super_admin/add_employee', [
            'sections' => $sections,
        ]);
    }

    /**
     * AJAX endpoint – search users with role_id = 4 (Employee — unassigned public employees)
     */
    public function searchUsers()
    {
        $request = service('request');
        $q = trim($request->getGet('q') ?? '');

        if (strlen($q) < 2) {
            return $this->response->setJSON([]);
        }

        $users = $this->userModel
            ->where('role_id', 4)
            ->like('name', $q)
            ->orderBy('name', 'ASC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON($users);
    }

    /**
     * Handle POST – promote a user to an employee role
     */
    public function addEmployee()
    {
        $request = service('request');

        $rules = [
            'user_id'        => 'required|integer',
            'section_id'     => 'required|integer',
            'role_id'        => 'required|in_list[2,3]',
            'expertise_ids'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid input. Please check all fields.');
        }

        $userId    = (int) $request->getPost('user_id');
        $sectionId = (int) $request->getPost('section_id');
        $roleId    = (int) $request->getPost('role_id');
        $expertiseIds = $request->getPost('expertise_ids') ?? [];

        // Make sure the user exists and is still an unassigned employee (role_id = 4)
        $user = $this->userModel->find($userId);
        if (! $user || (int) $user['role_id'] !== 4) {
            return redirect()->back()->with('error', 'User not found or is already an ICTU staff member.');
        }

        // Prevent assigning Head of Section if one already exists for the section
        if ($roleId === 2) {
            $existingHead = $this->userModel
                ->where('section_id', $sectionId)
                ->where('role_id', 2)
                ->first();

            if ($existingHead) {
                return redirect()->back()->with('error', 'This section already has a Head of Section (' . $existingHead['name'] . ').');
            }
        }

        $this->userModel->update($userId, [
            'section_id' => $sectionId,
            'role_id'    => $roleId,
        ]);

        // Sync expertise pivot
        $this->userExpertiseModel->syncForUser($userId, $expertiseIds);

        return redirect()->to('super-admin/employees')->with('success', $user['name'] . ' has been added as an employee.');
    }

    /**
     * AJAX endpoint – check if a section already has a Head of Section (role_id = 2)
     */
    public function checkHeadOfSection()
    {
        $sectionId = (int) $this->request->getGet('section_id');

        if (! $sectionId) {
            return $this->response->setJSON(['has_head' => false]);
        }

        $head = $this->userModel
            ->where('section_id', $sectionId)
            ->where('role_id', 2)
            ->first();

        return $this->response->setJSON([
            'has_head'  => $head !== null,
            'head_name' => $head ? $head['name'] : null,
        ]);
    }

    /**
     * AJAX endpoint – search expertise, optionally filtered by section
     */
    public function searchExpertise()
    {
        $q = trim($this->request->getGet('q') ?? '');
        $sectionId = $this->request->getGet('section_id');

        if (strlen($q) < 1) {
            return $this->response->setJSON([]);
        }

        $builder = $this->expertiseModel
            ->select('expertise.expertise_id, expertise.skill, sections.acronym')
            ->join('sections', 'sections.section_id = expertise.section_id', 'left')
            ->like('expertise.skill', $q)
            ->orderBy('expertise.skill', 'ASC')
            ->limit(20);

        if ($sectionId) {
            $builder->where('expertise.section_id', (int) $sectionId);
        }

        return $this->response->setJSON($builder->findAll());
    }

    // ─── Edit Employee ────────────────────────────────────

    /**
     * Show the edit employee page
     */
    public function editEmployeePage(int $id)
    {
        $employee = $this->userModel
            ->select('users.*, sections.name as section_name, sections.acronym')
            ->join('sections', 'users.section_id = sections.section_id', 'left')
            ->where('users.user_id', $id)
            ->where('users.role_id <=', 3)
            ->first();

        if (! $employee) {
            return redirect()->to('super-admin/employees')->with('error', 'Employee not found.');
        }

        $roleData              = (new RoleModel())->find((int) $employee['role_id']);
        $employee['role']      = $roleData['label']      ?? 'Unknown';
        $employee['role_color'] = $roleData['role_color'] ?? 'gray';
        $employee['initials'] = $this->userModel->get_initials($employee['name']);

        // Get employee's current expertise with skill names
        $expertiseIds = $this->userExpertiseModel->getExpertiseIdsForUser($id);
        $employeeExpertise = [];
        if (! empty($expertiseIds)) {
            $employeeExpertise = $this->expertiseModel
                ->select('expertise.expertise_id, expertise.skill, sections.acronym')
                ->join('sections', 'sections.section_id = expertise.section_id', 'left')
                ->whereIn('expertise.expertise_id', $expertiseIds)
                ->findAll();
        }

        $sections = $this->sectionModel->findAll();

        return view('super_admin/edit_employee', [
            'employee'          => $employee,
            'sections'          => $sections,
            'employeeExpertise' => $employeeExpertise,
        ]);
    }

    /**
     * Handle POST – update employee section/role (clears expertise if section changed)
     */
    public function updateEmployee(int $id)
    {
        $employee = $this->userModel->find($id);
        if (! $employee || (int) $employee['role_id'] > 3) {
            return redirect()->to('super-admin/employees')->with('error', 'Employee not found.');
        }

        $rules = [
            'section_id' => 'required|integer',
            'role_id'    => 'required|in_list[2,3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid input. Please check all fields.');
        }

        $newSectionId = (int) $this->request->getPost('section_id');
        $newRoleId    = (int) $this->request->getPost('role_id');
        $oldSectionId = (int) $employee['section_id'];

        // Prevent assigning Head of Section if one already exists (and it's not this employee)
        if ($newRoleId === 2) {
            $existingHead = $this->userModel
                ->where('section_id', $newSectionId)
                ->where('role_id', 2)
                ->where('user_id !=', $id)
                ->first();

            if ($existingHead) {
                return redirect()->back()->with('error', 'This section already has a Head of Section (' . $existingHead['name'] . ').');
            }
        }

        $this->userModel->update($id, [
            'section_id' => $newSectionId,
            'role_id'    => $newRoleId,
        ]);

        // If section changed, remove all expertise (expertise is section-specific)
        if ($newSectionId !== $oldSectionId) {
            $this->userExpertiseModel->where('user_id', $id)->delete();
            return redirect()->to('super-admin/employees/edit/' . $id)
                ->with('success', $employee['name'] . ' has been updated. Expertise was cleared because the section changed.');
        }

        return redirect()->to('super-admin/employees/edit/' . $id)
            ->with('success', $employee['name'] . ' has been updated successfully.');
    }

    /**
     * AJAX endpoint – remove a single expertise from an employee
     */
    public function removeEmployeeExpertise(int $userId, int $expertiseId)
    {
        $employee = $this->userModel->find($userId);
        if (! $employee || (int) $employee['role_id'] > 3) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Employee not found.']);
        }

        $this->userExpertiseModel
            ->where('user_id', $userId)
            ->where('expertise_id', $expertiseId)
            ->delete();

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * AJAX endpoint – add a single expertise to an employee
     */
    public function addEmployeeExpertise(int $userId)
    {
        $employee = $this->userModel->find($userId);
        if (! $employee || (int) $employee['role_id'] > 3) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Employee not found.']);
        }

        $expertiseId = (int) $this->request->getPost('expertise_id');
        if (! $expertiseId) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing expertise_id.']);
        }

        // Avoid duplicates
        $exists = $this->userExpertiseModel
            ->where('user_id', $userId)
            ->where('expertise_id', $expertiseId)
            ->first();

        if (! $exists) {
            $this->userExpertiseModel->insert([
                'user_id'      => $userId,
                'expertise_id' => $expertiseId,
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function settings()
    {
        return view('super_admin/settings');
    }

    // ─── Buildings ────────────────────────────────────────

    public function buildings()
    {
        return view('super_admin/buildings', [
            'buildings' => $this->buildingModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function addBuildingPage()
    {
        return view('super_admin/add_building');
    }

    public function addBuilding()
    {
        $rules = [
            'name' => 'required|max_length[255]|is_unique[buildings.name]',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/add_building', [
                'validation' => $this->validator,
            ]);
        }

        $checkIfExists = $this->buildingModel->where('name', trim($this->request->getPost('name')))->first();
        if ($checkIfExists) {
            return view('super_admin/add_building', [
                'validation' => $this->validator->setError('name', 'A building with this name already exists.'),
            ]);
        }

        $this->buildingModel->insert([
            'name' => trim($this->request->getPost('name')),
        ]);

        return redirect()->to('super-admin/buildings')->with('success', 'Building added successfully.');
    }

    public function editBuildingPage(int $id)
    {
        $building = $this->buildingModel->find($id);
        if (! $building) {
            return redirect()->to('super-admin/buildings')->with('error', 'Building not found.');
        }

        return view('super_admin/edit_building', [
            'building' => $building,
        ]);
    }

    public function updateBuilding(int $id)
    {
        $building = $this->buildingModel->find($id);
        if (! $building) {
            return redirect()->to('super-admin/buildings')->with('error', 'Building not found.');
        }

        $rules = [
            'name' => "required|max_length[255]|is_unique[buildings.name,building_id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/edit_building', [
                'building'   => $building,
                'validation' => $this->validator,
            ]);
        }

        $this->buildingModel->update($id, [
            'name' => trim($this->request->getPost('name')),
        ]);

        return redirect()->to('super-admin/buildings/edit/' . $id)->with('success', 'Building updated successfully.');
    }

    public function deleteBuilding(int $id)
    {
        $building = $this->buildingModel->find($id);
        if (! $building) {
            return redirect()->to('super-admin/buildings')->with('error', 'Building not found.');
        }

        $this->buildingModel->delete($id);

        return redirect()->to('super-admin/buildings')->with('success', '"' . $building['name'] . '" has been deleted.');
    }

    // ─── Expertise ─────────────────────────────────────────

    public function expertise()
    {
        $expertiseList = $this->expertiseModel
            ->select('expertise.*, sections.acronym')
            ->join('sections', 'sections.section_id = expertise.section_id', 'left')
            ->orderBy('expertise.skill', 'ASC')
            ->findAll();

        $sectionColorMap = ['MIS' => 'blue', 'NICM' => 'green', 'ICTRAM' => 'yellow'];
        foreach ($expertiseList as &$expertise) {
            $expertise['section_color'] = $sectionColorMap[$expertise['acronym']] ?? 'gray';
        }

        return view('super_admin/expertise', [
            'expertiseList' => $expertiseList,
        ]);
    }

    public function addExpertisePage()
    {
        return view('super_admin/add_expertise', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addExpertise()
    {
        $rules = [
            'skill'      => 'required|max_length[255]|is_unique[expertise.skill]',
            'section_id' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/add_expertise', [
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation'  => $this->validator,
            ]);
        }

        $this->expertiseModel->insert([
            'skill'       => trim($this->request->getPost('skill')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'section_id'  => (int) $this->request->getPost('section_id'),
        ]);

        return redirect()->to('super-admin/expertise')->with('success', 'Expertise added successfully.');
    }

    public function editExpertisePage(int $id)
    {
        $expertise = $this->expertiseModel->find($id);
        if (! $expertise) {
            return redirect()->to('super-admin/expertise')->with('error', 'Expertise not found.');
        }

        return view('super_admin/edit_expertise', [
            'expertise' => $expertise,
            'sections'  => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateExpertise(int $id)
    {
        $expertise = $this->expertiseModel->find($id);
        if (! $expertise) {
            return redirect()->to('super-admin/expertise')->with('error', 'Expertise not found.');
        }

        $rules = [
            'skill'      => "required|max_length[255]|is_unique[expertise.skill,expertise_id,{$id}]",
            'section_id' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/edit_expertise', [
                'expertise'  => $expertise,
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation'  => $this->validator,
            ]);
        }

        $this->expertiseModel->update($id, [
            'skill'       => trim($this->request->getPost('skill')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'section_id'  => (int) $this->request->getPost('section_id'),
        ]);

        return redirect()->to('super-admin/expertise/edit/' . $id)->with('success', 'Expertise updated successfully.');
    }

    public function deleteExpertise(int $id)
    {
        $expertise = $this->expertiseModel->find($id);
        if (! $expertise) {
            return redirect()->to('super-admin/expertise')->with('error', 'Expertise not found.');
        }

        $this->expertiseModel->delete($id);

        return redirect()->to('super-admin/expertise')->with('success', '"' . $expertise['skill'] . '" has been deleted.');
    }

    // ─── Issue Types ──────────────────────────────────────

    public function issueTypes()
    {
        $list = $this->issueTypeModel
            ->select('issue_types.*, sections.acronym')
            ->join('sections', 'sections.section_id = issue_types.section_id', 'left')
            ->orderBy('issue_types.issue_type_name', 'ASC')
            ->findAll();

        return view('super_admin/issue_types', ['issueTypes' => $list]);
    }

    public function addIssueTypePage()
    {
        return view('super_admin/add_issue_type', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addIssueType()
    {
        $rules = [
            'issue_type_name'   => 'required|max_length[255]|is_unique[issue_types.issue_type_name]',
            'issue_type_domain' => 'required|max_length[255]',
            'section_id'        => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_issue_type', [
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->issueTypeModel->insert([
            'issue_type_name'   => trim($this->request->getPost('issue_type_name')),
            'issue_type_domain' => trim($this->request->getPost('issue_type_domain')),
            'description'       => trim($this->request->getPost('description') ?? ''),
            'section_id'        => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/issue-types')->with('success', 'Issue type added successfully.');
    }

    public function editIssueTypePage(int $id)
    {
        $item = $this->issueTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/issue-types')->with('error', 'Issue type not found.');
        return view('super_admin/edit_issue_type', [
            'issueType' => $item,
            'sections'  => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateIssueType(int $id)
    {
        $item = $this->issueTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/issue-types')->with('error', 'Issue type not found.');

        $rules = [
            'issue_type_name'   => "required|max_length[255]|is_unique[issue_types.issue_type_name,issue_type_id,{$id}]",
            'issue_type_domain' => 'required|max_length[255]',
            'section_id'        => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_issue_type', [
                'issueType'  => $item,
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->issueTypeModel->update($id, [
            'issue_type_name'   => trim($this->request->getPost('issue_type_name')),
            'issue_type_domain' => trim($this->request->getPost('issue_type_domain')),
            'description'       => trim($this->request->getPost('description') ?? ''),
            'section_id'        => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/issue-types/edit/' . $id)->with('success', 'Issue type updated successfully.');
    }

    public function deleteIssueType(int $id)
    {
        $item = $this->issueTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/issue-types')->with('error', 'Issue type not found.');
        $this->issueTypeModel->delete($id);
        return redirect()->to('super-admin/issue-types')->with('success', '"' . $item['issue_type_name'] . '" has been deleted.');
    }

    // ─── Organizational Units ─────────────────────────────

    public function organizationalUnits()
    {
        $list = $this->orgUnitModel
            ->select('organizational_units.*, buildings.name as building_name')
            ->join('buildings', 'buildings.building_id = organizational_units.building_id', 'left')
            ->orderBy('organizational_units.name', 'ASC')
            ->findAll();

        return view('super_admin/organizational_units', ['orgUnits' => $list]);
    }

    public function addOrgUnitPage()
    {
        return view('super_admin/add_organizational_unit', [
            'buildings' => $this->buildingModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function addOrgUnit()
    {
        $rules = [
            'name'        => 'required|max_length[255]|is_unique[organizational_units.name]',
            'building_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_organizational_unit', [
                'buildings'  => $this->buildingModel->orderBy('name', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->orgUnitModel->insert([
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'building_id' => (int) $this->request->getPost('building_id'),
        ]);
        return redirect()->to('super-admin/organizational-units')->with('success', 'Organizational unit added successfully.');
    }

    public function editOrgUnitPage(int $id)
    {
        $item = $this->orgUnitModel->find($id);
        if (! $item) return redirect()->to('super-admin/organizational-units')->with('error', 'Unit not found.');
        return view('super_admin/edit_organizational_unit', [
            'orgUnit'   => $item,
            'buildings' => $this->buildingModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function updateOrgUnit(int $id)
    {
        $item = $this->orgUnitModel->find($id);
        if (! $item) return redirect()->to('super-admin/organizational-units')->with('error', 'Unit not found.');

        $rules = [
            'name'        => "required|max_length[255]|is_unique[organizational_units.name,unit_id,{$id}]",
            'building_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_organizational_unit', [
                'orgUnit'    => $item,
                'buildings'  => $this->buildingModel->orderBy('name', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->orgUnitModel->update($id, [
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'building_id' => (int) $this->request->getPost('building_id'),
        ]);
        return redirect()->to('super-admin/organizational-units/edit/' . $id)->with('success', 'Organizational unit updated successfully.');
    }

    public function deleteOrgUnit(int $id)
    {
        $item = $this->orgUnitModel->find($id);
        if (! $item) return redirect()->to('super-admin/organizational-units')->with('error', 'Unit not found.');
        $this->orgUnitModel->delete($id);
        return redirect()->to('super-admin/organizational-units')->with('success', '"' . $item['name'] . '" has been deleted.');
    }

    // ─── Priority Levels ──────────────────────────────────

    public function priorityLevels()
    {
        return view('super_admin/priority_levels', [
            'priorityLevels' => $this->priorityLevelModel->orderBy('priority_name', 'ASC')->findAll(),
        ]);
    }

    public function addPriorityLevelPage()
    {
        return view('super_admin/add_priority_level');
    }

    public function addPriorityLevel()
    {
        $rules = [
            'priority_name'    => 'required|max_length[255]|is_unique[priority_levels.priority_name]',
            'operation_status' => 'required|max_length[255]',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_priority_level', ['validation' => $this->validator]);
        }
        $this->priorityLevelModel->insert([
            'priority_name'    => trim($this->request->getPost('priority_name')),
            'operation_status' => trim($this->request->getPost('operation_status')),
            'description'      => trim($this->request->getPost('description') ?? ''),
        ]);
        return redirect()->to('super-admin/priority-levels')->with('success', 'Priority level added successfully.');
    }

    public function editPriorityLevelPage(int $id)
    {
        $item = $this->priorityLevelModel->find($id);
        if (! $item) return redirect()->to('super-admin/priority-levels')->with('error', 'Priority level not found.');
        return view('super_admin/edit_priority_level', ['priorityLevel' => $item]);
    }

    public function updatePriorityLevel(int $id)
    {
        $item = $this->priorityLevelModel->find($id);
        if (! $item) return redirect()->to('super-admin/priority-levels')->with('error', 'Priority level not found.');

        $rules = [
            'priority_name'    => "required|max_length[255]|is_unique[priority_levels.priority_name,priority_level_id,{$id}]",
            'operation_status' => 'required|max_length[255]',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_priority_level', [
                'priorityLevel' => $item,
                'validation'    => $this->validator,
            ]);
        }
        $this->priorityLevelModel->update($id, [
            'priority_name'    => trim($this->request->getPost('priority_name')),
            'operation_status' => trim($this->request->getPost('operation_status')),
            'description'      => trim($this->request->getPost('description') ?? ''),
        ]);
        return redirect()->to('super-admin/priority-levels/edit/' . $id)->with('success', 'Priority level updated successfully.');
    }

    public function deletePriorityLevel(int $id)
    {
        $item = $this->priorityLevelModel->find($id);
        if (! $item) return redirect()->to('super-admin/priority-levels')->with('error', 'Priority level not found.');
        $this->priorityLevelModel->delete($id);
        return redirect()->to('super-admin/priority-levels')->with('success', '"' . $item['priority_name'] . '" has been deleted.');
    }

    // ─── Request Actions ──────────────────────────────────

    public function requestActions()
    {
        $list = $this->requestActionModel
            ->select('request_actions.*, request_types.request_type_name, sections.acronym')
            ->join('request_types', 'request_types.request_type_id = request_actions.request_type_id', 'left')
            ->join('sections', 'sections.section_id = request_actions.section_id', 'left')
            ->orderBy('request_actions.action_name', 'ASC')
            ->findAll();

        return view('super_admin/request_actions', ['requestActions' => $list]);
    }

    public function addRequestActionPage()
    {
        return view('super_admin/add_request_action', [
            'requestTypes' => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
            'sections'     => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addRequestAction()
    {
        $rules = [
            'action_name'     => 'required|max_length[255]',
            'request_type_id' => 'required|integer',
            'section_id'      => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_request_action', [
                'requestTypes' => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
                'sections'     => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation'   => $this->validator,
            ]);
        }
        $this->requestActionModel->insert([
            'action_name'     => trim($this->request->getPost('action_name')),
            'request_type_id' => (int) $this->request->getPost('request_type_id'),
            'section_id'      => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/request-actions')->with('success', 'Request action added successfully.');
    }

    public function editRequestActionPage(int $id)
    {
        $item = $this->requestActionModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-actions')->with('error', 'Request action not found.');
        return view('super_admin/edit_request_action', [
            'requestAction' => $item,
            'requestTypes'  => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
            'sections'      => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateRequestAction(int $id)
    {
        $item = $this->requestActionModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-actions')->with('error', 'Request action not found.');

        $rules = [
            'action_name'     => 'required|max_length[255]',
            'request_type_id' => 'required|integer',
            'section_id'      => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_request_action', [
                'requestAction' => $item,
                'requestTypes'  => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
                'sections'      => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation'    => $this->validator,
            ]);
        }
        $this->requestActionModel->update($id, [
            'action_name'     => trim($this->request->getPost('action_name')),
            'request_type_id' => (int) $this->request->getPost('request_type_id'),
            'section_id'      => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/request-actions/edit/' . $id)->with('success', 'Request action updated successfully.');
    }

    public function deleteRequestAction(int $id)
    {
        $item = $this->requestActionModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-actions')->with('error', 'Request action not found.');
        $this->requestActionModel->delete($id);
        return redirect()->to('super-admin/request-actions')->with('success', '"' . $item['action_name'] . '" has been deleted.');
    }

    // ─── Request Platforms ────────────────────────────────

    public function requestPlatforms()
    {
        $list = $this->requestPlatformModel
            ->select('request_platforms.*, request_types.request_type_name')
            ->join('request_types', 'request_types.request_type_id = request_platforms.request_type_id', 'left')
            ->orderBy('request_platforms.platform_name', 'ASC')
            ->findAll();

        return view('super_admin/request_platforms', ['requestPlatforms' => $list]);
    }

    public function addRequestPlatformPage()
    {
        return view('super_admin/add_request_platform', [
            'requestTypes' => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
        ]);
    }

    public function addRequestPlatform()
    {
        $rules = [
            'platform_name'   => 'required|max_length[255]',
            'request_type_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_request_platform', [
                'requestTypes' => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
                'validation'   => $this->validator,
            ]);
        }
        $this->requestPlatformModel->insert([
            'platform_name'        => trim($this->request->getPost('platform_name')),
            'platform_description' => trim($this->request->getPost('platform_description') ?? ''),
            'request_type_id'      => (int) $this->request->getPost('request_type_id'),
        ]);
        return redirect()->to('super-admin/request-platforms')->with('success', 'Request platform added successfully.');
    }

    public function editRequestPlatformPage(int $id)
    {
        $item = $this->requestPlatformModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-platforms')->with('error', 'Request platform not found.');
        return view('super_admin/edit_request_platform', [
            'requestPlatform' => $item,
            'requestTypes'    => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
        ]);
    }

    public function updateRequestPlatform(int $id)
    {
        $item = $this->requestPlatformModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-platforms')->with('error', 'Request platform not found.');

        $rules = [
            'platform_name'   => 'required|max_length[255]',
            'request_type_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_request_platform', [
                'requestPlatform' => $item,
                'requestTypes'    => $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll(),
                'validation'      => $this->validator,
            ]);
        }
        $this->requestPlatformModel->update($id, [
            'platform_name'        => trim($this->request->getPost('platform_name')),
            'platform_description' => trim($this->request->getPost('platform_description') ?? ''),
            'request_type_id'      => (int) $this->request->getPost('request_type_id'),
        ]);
        return redirect()->to('super-admin/request-platforms/edit/' . $id)->with('success', 'Request platform updated successfully.');
    }

    public function deleteRequestPlatform(int $id)
    {
        $item = $this->requestPlatformModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-platforms')->with('error', 'Request platform not found.');
        $this->requestPlatformModel->delete($id);
        return redirect()->to('super-admin/request-platforms')->with('success', '"' . $item['platform_name'] . '" has been deleted.');
    }

    // ─── Request Types ────────────────────────────────────

    public function requestTypes()
    {
        $list = $this->requestTypeModel
            ->select('request_types.*, sections.acronym')
            ->join('sections', 'sections.section_id = request_types.section_id', 'left')
            ->orderBy('request_types.request_type_name', 'ASC')
            ->findAll();

        return view('super_admin/request_types', ['requestTypes' => $list]);
    }

    public function addRequestTypePage()
    {
        return view('super_admin/add_request_type', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addRequestType()
    {
        $rules = [
            'request_type_name' => 'required|max_length[255]|is_unique[request_types.request_type_name]',
            'section_id'        => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_request_type', [
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->requestTypeModel->insert([
            'request_type_name' => trim($this->request->getPost('request_type_name')),
            'section_id'        => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/request-types')->with('success', 'Request type added successfully.');
    }

    public function editRequestTypePage(int $id)
    {
        $item = $this->requestTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-types')->with('error', 'Request type not found.');
        return view('super_admin/edit_request_type', [
            'requestType' => $item,
            'sections'    => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateRequestType(int $id)
    {
        $item = $this->requestTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-types')->with('error', 'Request type not found.');

        $rules = [
            'request_type_name' => "required|max_length[255]|is_unique[request_types.request_type_name,request_type_id,{$id}]",
            'section_id'        => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_request_type', [
                'requestType' => $item,
                'sections'    => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation'  => $this->validator,
            ]);
        }
        $this->requestTypeModel->update($id, [
            'request_type_name' => trim($this->request->getPost('request_type_name')),
            'section_id'        => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/request-types/edit/' . $id)->with('success', 'Request type updated successfully.');
    }

    public function deleteRequestType(int $id)
    {
        $item = $this->requestTypeModel->find($id);
        if (! $item) return redirect()->to('super-admin/request-types')->with('error', 'Request type not found.');
        $this->requestTypeModel->delete($id);
        return redirect()->to('super-admin/request-types')->with('success', '"' . $item['request_type_name'] . '" has been deleted.');
    }

    // ─── Ticket Equipment ─────────────────────────────────

    public function ticketEquipment()
    {
        $list = $this->ticketEquipmentModel
            ->select('ticket_equipments.*, sections.acronym')
            ->join('sections', 'sections.section_id = ticket_equipments.section_id', 'left')
            ->orderBy('ticket_equipments.name', 'ASC')
            ->findAll();

        return view('super_admin/ticket_equipment', ['ticketEquipment' => $list]);
    }

    public function addTicketEquipmentPage()
    {
        return view('super_admin/add_ticket_equipment', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addTicketEquipment()
    {
        $rules = [
            'name'       => 'required|max_length[255]|is_unique[ticket_equipments.name]',
            'section_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/add_ticket_equipment', [
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->ticketEquipmentModel->insert([
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'section_id'  => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/ticket-equipment')->with('success', 'Equipment added successfully.');
    }

    public function editTicketEquipmentPage(int $id)
    {
        $item = $this->ticketEquipmentModel->find($id);
        if (! $item) return redirect()->to('super-admin/ticket-equipment')->with('error', 'Equipment not found.');
        return view('super_admin/edit_ticket_equipment', [
            'equipment' => $item,
            'sections'  => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateTicketEquipment(int $id)
    {
        $item = $this->ticketEquipmentModel->find($id);
        if (! $item) return redirect()->to('super-admin/ticket-equipment')->with('error', 'Equipment not found.');

        $rules = [
            'name'       => "required|max_length[255]|is_unique[ticket_equipments.name,equipment_id,{$id}]",
            'section_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return view('super_admin/edit_ticket_equipment', [
                'equipment'  => $item,
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }
        $this->ticketEquipmentModel->update($id, [
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'section_id'  => (int) $this->request->getPost('section_id'),
        ]);
        return redirect()->to('super-admin/ticket-equipment/edit/' . $id)->with('success', 'Equipment updated successfully.');
    }

    public function deleteTicketEquipment(int $id)
    {
        $item = $this->ticketEquipmentModel->find($id);
        if (! $item) return redirect()->to('super-admin/ticket-equipment')->with('error', 'Equipment not found.');
        $this->ticketEquipmentModel->delete($id);
        return redirect()->to('super-admin/ticket-equipment')->with('success', '"' . $item['name'] . '" has been deleted.');
    }

    // ═══════════════════════════════════════════════════════
    //  SECTION ACCESS CONTROL
    // ═══════════════════════════════════════════════════════

    /**
     * Display the section-access matrix for Employee & Student roles.
     */
    public function sectionAccess()
    {
        $sections = $this->sectionModel->orderBy('acronym', 'ASC')->findAll();
        $roles    = [
            4 => 'Employee',
            5 => 'Student',
        ];

        $matrix = $this->sectionRoleAccessModel->getAccessMatrix(array_keys($roles));

        return view('super_admin/section_access', [
            'sections' => $sections,
            'roles'    => $roles,
            'matrix'   => $matrix,
        ]);
    }

    /**
     * Save the section-access matrix (POST).
     */
    public function updateSectionAccess()
    {
        $sections = $this->sectionModel->findAll();
        $roleIds  = [4, 5];
        $posted   = $this->request->getPost('access') ?? [];

        foreach ($roleIds as $roleId) {
            foreach ($sections as $s) {
                $key     = $roleId . '_' . $s['section_id'];
                $enabled = isset($posted[$key]);
                $this->sectionRoleAccessModel->setAccess($roleId, (int) $s['section_id'], $enabled);
            }
        }

        return redirect()->to('super-admin/section-access')->with('success', 'Section access permissions updated successfully.');
    }

    // ═══════════════════════════════════════════════════════
    //  KEYWORD RULES CRUD
    // ═══════════════════════════════════════════════════════

    public function keywordRules()
    {
        $list = $this->keywordRuleModel->getAllWithSection();
        return view('super_admin/keyword_rules', ['keywordRules' => $list]);
    }

    public function addKeywordRulePage()
    {
        return view('super_admin/add_keyword_rule', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addKeywordRule()
    {
        $isDefault = (bool) $this->request->getPost('is_default');

        $rules = [
            'section_id' => 'required|integer',
            'keyword'    => $isDefault ? 'permit_empty|max_length[100]' : 'required|max_length[100]',
            'tip_title'  => 'permit_empty|max_length[255]',
            'tip_body'   => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/add_keyword_rule', [
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }

        $keyword = $isDefault ? '_default' : strtolower(trim($this->request->getPost('keyword')));

        $this->keywordRuleModel->insert([
            'section_id' => (int) $this->request->getPost('section_id'),
            'keyword'    => $keyword,
            'tip_title'  => trim($this->request->getPost('tip_title') ?? ''),
            'tip_body'   => trim($this->request->getPost('tip_body') ?? ''),
            'is_default' => $isDefault ? 1 : 0,
            'is_active'  => 1,
        ]);

        return redirect()->to('super-admin/keyword-rules')->with('success', 'Keyword rule added successfully.');
    }

    public function editKeywordRulePage(int $id)
    {
        $item = $this->keywordRuleModel->find($id);
        if (! $item) return redirect()->to('super-admin/keyword-rules')->with('error', 'Keyword rule not found.');

        return view('super_admin/edit_keyword_rule', [
            'rule'     => $item,
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function updateKeywordRule(int $id)
    {
        $item = $this->keywordRuleModel->find($id);
        if (! $item) return redirect()->to('super-admin/keyword-rules')->with('error', 'Keyword rule not found.');

        $isDefault = (bool) $this->request->getPost('is_default');

        $rules = [
            'section_id' => 'required|integer',
            'keyword'    => $isDefault ? 'permit_empty|max_length[100]' : 'required|max_length[100]',
            'tip_title'  => 'permit_empty|max_length[255]',
            'tip_body'   => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/edit_keyword_rule', [
                'rule'       => $item,
                'sections'   => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
                'validation' => $this->validator,
            ]);
        }

        $keyword = $isDefault ? '_default' : strtolower(trim($this->request->getPost('keyword')));

        $this->keywordRuleModel->update($id, [
            'section_id' => (int) $this->request->getPost('section_id'),
            'keyword'    => $keyword,
            'tip_title'  => trim($this->request->getPost('tip_title') ?? ''),
            'tip_body'   => trim($this->request->getPost('tip_body') ?? ''),
            'is_default' => $isDefault ? 1 : 0,
            'is_active'  => (int) ($this->request->getPost('is_active') ?? 1),
        ]);

        return redirect()->to('super-admin/keyword-rules/edit/' . $id)->with('success', 'Keyword rule updated successfully.');
    }

    public function deleteKeywordRule(int $id)
    {
        $item = $this->keywordRuleModel->find($id);
        if (! $item) return redirect()->to('super-admin/keyword-rules')->with('error', 'Keyword rule not found.');

        $this->keywordRuleModel->delete($id);
        return redirect()->to('super-admin/keyword-rules')->with('success', '"' . $item['keyword'] . '" has been deleted.');
    }

    // ─── Sections ─────────────────────────────────────────

    public function sections()
    {
        return view('super_admin/sections', [
            'sections' => $this->sectionModel->orderBy('acronym', 'ASC')->findAll(),
        ]);
    }

    public function addSectionPage()
    {
        return view('super_admin/add_section');
    }

    public function addSection()
    {
        $rules = [
            'acronym'     => 'required|max_length[20]|is_unique[sections.acronym]',
            'name'        => 'required|max_length[255]|is_unique[sections.name]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/add_section', [
                'validation' => $this->validator,
            ]);
        }

        $this->sectionModel->insert([
            'acronym'     => strtoupper(trim($this->request->getPost('acronym'))),
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
        ]);

        return redirect()->to('super-admin/sections')->with('success', 'Section added successfully.');
    }

    public function editSectionPage(int $id)
    {
        $section = $this->sectionModel->find($id);
        if (! $section) {
            return redirect()->to('super-admin/sections')->with('error', 'Section not found.');
        }

        return view('super_admin/edit_section', [
            'section' => $section,
        ]);
    }

    public function updateSection(int $id)
    {
        $section = $this->sectionModel->find($id);
        if (! $section) {
            return redirect()->to('super-admin/sections')->with('error', 'Section not found.');
        }

        $rules = [
            'acronym'     => "required|max_length[20]|is_unique[sections.acronym,section_id,{$id}]",
            'name'        => "required|max_length[255]|is_unique[sections.name,section_id,{$id}]",
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return view('super_admin/edit_section', [
                'section'    => $section,
                'validation' => $this->validator,
            ]);
        }

        $this->sectionModel->update($id, [
            'acronym'     => strtoupper(trim($this->request->getPost('acronym'))),
            'name'        => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description') ?? ''),
        ]);

        return redirect()->to('super-admin/sections/edit/' . $id)->with('success', 'Section updated successfully.');
    }

    public function deleteSection(int $id)
    {
        $section = $this->sectionModel->find($id);
        if (! $section) {
            return redirect()->to('super-admin/sections')->with('error', 'Section not found.');
        }

        $this->sectionModel->delete($id);

        return redirect()->to('super-admin/sections')->with('success', '"' . $section['name'] . '" has been deleted.');
    }

    // ═══════════════════════════════════════════════════════
    //  FORM OPTION ACCESS CONTROL
    // ═══════════════════════════════════════════════════════

    /**
     * Display the form-option access matrix page.
     * Shows toggle grids for Request Types, Request Actions,
     * Request Platforms, and Equipment — per Employee / Student role.
     */
    public function formOptionAccess()
    {
        $roles = [
            4 => 'Employee',
            5 => 'Student',
        ];
        $roleIds = array_keys($roles);

        $sections = $this->sectionModel->orderBy('acronym', 'ASC')->findAll();

        // Fetch items grouped by section
        $requestTypes  = $this->requestTypeModel->orderBy('request_type_name', 'ASC')->findAll();
        $requestActions = $this->requestActionModel->orderBy('action_name', 'ASC')->findAll();
        $requestPlatforms = $this->requestPlatformModel->orderBy('platform_name', 'ASC')->findAll();
        $equipment     = $this->ticketEquipmentModel->orderBy('name', 'ASC')->findAll();

        // Fetch access matrices
        $matrices = [
            'request_type'     => $this->formOptionRoleAccessModel->getAccessMatrix('request_type', $roleIds),
            'request_action'   => $this->formOptionRoleAccessModel->getAccessMatrix('request_action', $roleIds),
            'request_platform' => $this->formOptionRoleAccessModel->getAccessMatrix('request_platform', $roleIds),
            'equipment'        => $this->formOptionRoleAccessModel->getAccessMatrix('equipment', $roleIds),
        ];

        return view('super_admin/form_option_access', [
            'roles'            => $roles,
            'sections'         => $sections,
            'requestTypes'     => $requestTypes,
            'requestActions'   => $requestActions,
            'requestPlatforms' => $requestPlatforms,
            'equipment'        => $equipment,
            'matrices'         => $matrices,
        ]);
    }

    /**
     * Save the form-option access toggles (POST).
     */
    public function updateFormOptionAccess()
    {
        $roleIds = [4, 5];
        $posted  = $this->request->getPost('access') ?? [];

        // Map of option_type => [items]
        $optionGroups = [
            'request_type'     => ['items' => $this->requestTypeModel->findAll(),      'pk' => 'request_type_id'],
            'request_action'   => ['items' => $this->requestActionModel->findAll(),     'pk' => 'action_id'],
            'request_platform' => ['items' => $this->requestPlatformModel->findAll(),   'pk' => 'platform_id'],
            'equipment'        => ['items' => $this->ticketEquipmentModel->findAll(),   'pk' => 'equipment_id'],
        ];

        foreach ($optionGroups as $optionType => $meta) {
            foreach ($roleIds as $roleId) {
                foreach ($meta['items'] as $item) {
                    $optionId = (int) $item[$meta['pk']];
                    $key      = $optionType . '_' . $roleId . '_' . $optionId;
                    $enabled  = isset($posted[$key]);
                    $this->formOptionRoleAccessModel->setAccess($optionType, $optionId, $roleId, $enabled);
                }
            }
        }

        return redirect()->to('super-admin/form-option-access')
                         ->with('success', 'Form option access permissions updated successfully.');
    }
}
