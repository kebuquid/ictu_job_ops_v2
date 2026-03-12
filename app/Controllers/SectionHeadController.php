<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\SectionModel;
use App\Models\UserModel;
use App\Models\KeywordRuleModel;
use App\Models\ResponsePartModel;
use App\Models\TicketHistoryModel;
use App\Models\JobStatusModel;
use App\Models\RoleModel;

class SectionHeadController extends BaseController
{
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private SectionModel $sectionModel;
    private UserModel $userModel;
    private KeywordRuleModel $keywordRuleModel;
    private ResponsePartModel $responsePartModel;
    private TicketHistoryModel $ticketHistoryModel;

    public function __construct()
    {
        $this->jobTicketModel         = new JobTicketModel();
        $this->jobTicketRequestModel  = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->sectionModel           = new SectionModel();
        $this->userModel              = new UserModel();
        $this->keywordRuleModel       = new KeywordRuleModel();
        $this->responsePartModel      = new ResponsePartModel();
        $this->ticketHistoryModel     = new TicketHistoryModel();
    }

    /**
     * Get the current user's ID from session.
     */
    private function userId(): int
    {
        return (int) (session()->get('user')['user_id'] ?? 0);
    }

    /**
     * Get the current user's section_id from session.
     */
    private function sectionId(): int
    {
        return (int) (session()->get('user')['section_id'] ?? 0);
    }

    /**
     * Dashboard – section-scoped stats + recent tickets.
     */
    public function dashboard()
    {
        $sectionId = $this->sectionId();

        // Build stats by joining job_ticket_requests to scope by section
        $builder = $this->jobTicketModel
            ->select('job_tickets.job_status, COUNT(*) as cnt')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->groupBy('job_tickets.job_status');

        $rows = $builder->findAll();

        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
        $completedId  = JobStatusModel::getIdByLabel('Completed');

        $stats = ['total' => 0, 'open' => 0, 'in_progress' => 0, 'completed' => 0, 'pending_verification' => 0];
        foreach ($rows as $r) {
            $stats['total'] += (int) $r['cnt'];
            match ((int) $r['job_status']) {
                $openId       => $stats['open'] += (int) $r['cnt'],
                $inProgressId => $stats['in_progress'] += (int) $r['cnt'],
                $completedId  => $stats['completed'] += (int) $r['cnt'],
                default => null,
            };
        }

        // Pending verification: completed tickets in this section where verifier_id is null
        $stats['pending_verification'] = $this->jobTicketResponseModel
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->where('job_ticket_responses.completion_status IS NOT NULL')
            ->where('job_ticket_responses.verifier_id IS NULL')
            ->countAllResults();

        // Recent tickets
        $recentTickets = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.problem_description, job_ticket_requests.section_id, users.name as staff_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->limit(8)
            ->findAll();

        // Section staff with active ticket counts
        $sectionStaff = $this->userModel
            ->whereIn('role_id', [3])
            ->where('section_id', $sectionId)
            ->findAll();

        foreach ($sectionStaff as &$staff) {
            $staff['active_tickets'] = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $staff['user_id'])
                ->whereIn('job_tickets.job_status', [$openId, $inProgressId])
                ->countAllResults();
        }

        return view('section_heads/dashboard', [
            'stats'         => $stats,
            'recentTickets' => $recentTickets,
            'sectionStaff'  => $sectionStaff,
        ]);
    }

    /**
     * All tickets in this section.
     */
    public function tickets()
    {
        $sectionId = $this->sectionId();

        $tickets = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.problem_description, job_ticket_requests.priority_level, job_ticket_responses.job_ticket_response_id, users.name as staff_name, requestor.name as requestor_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->findAll();

        return view('section_heads/tickets', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Section employees list.
     */
    public function employees()
    {
        $sectionId = $this->sectionId();

        $employees = $this->userModel
            ->select('users.*, sections.name as section_name, sections.acronym')
            ->join('sections', 'users.section_id = sections.section_id', 'left')
            ->where('users.section_id', $sectionId)
            ->whereIn('users.role_id', [3])
            ->findAll();

        $roleModel = new RoleModel();
        foreach ($employees as &$emp) {
            $emp['initials']    = $this->userModel->get_initials($emp['name']);
            $roleData           = $roleModel->find((int) $emp['role_id']);
            $emp['role']        = $roleData['label']      ?? 'Unknown';
            $emp['role_color']  = $roleData['role_color'] ?? 'gray';
        }

        return view('section_heads/employees', [
            'employees' => $employees,
        ]);
    }

    /**
     * List tickets pending verification.
     */
    public function verify()
    {
        $sectionId = $this->sectionId();

        $pendingTickets = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.problem_description, users.name as staff_name')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->where('job_ticket_responses.completion_status IS NOT NULL')
            ->where('job_ticket_responses.verifier_id IS NULL')
            ->orderBy('job_ticket_responses.completion_date', 'DESC')
            ->findAll();

        return view('section_heads/verify', [
            'pendingTickets' => $pendingTickets,
        ]);
    }

    /**
     * Process verification of a ticket response.
     */
    public function verifyTicket(int $responseId)
    {
        $userId = (int) session()->get('user')['user_id'];

        $this->jobTicketResponseModel->update($responseId, [
            'verifier_id'  => $userId,
            'verified_date' => date('Y-m-d H:i:s'),
        ]);

        // Also close the parent ticket
        $response = $this->jobTicketResponseModel->find($responseId);
        if ($response) {
            $completedId = JobStatusModel::getIdByLabel('Completed');
            $closedId    = JobStatusModel::getIdByLabel('Closed');
            $this->jobTicketModel->update($response['job_ticket_id'], [
                'job_status' => $closedId,
            ]);

            // Log: verified
            $this->ticketHistoryModel->log(
                (int) $response['job_ticket_id'],
                'verified',
                $completedId,
                $closedId,
                $userId,
                'Verified and closed by section head'
            );

            // Notify the requestor their ticket has been verified and closed
            $this->sendStatusUpdateEmail(
                (int) $response['job_ticket_id'],
                'closed',
                'Closed',
                'Your ticket has been verified and officially closed by the section head. Thank you for using our services.'
            );
        }

        return redirect()->to('admin/verify')->with('success', 'Ticket response verified and closed.');
    }

    /**
     * My assigned tickets (tickets where this section head is the assigned staff).
     */
    public function myTickets()
    {
        $userId = $this->userId();

        $tickets = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_tickets.created_at as ticket_date, job_ticket_requests.problem_description, job_ticket_requests.priority_level, requestor.name as requestor_name')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->where('job_ticket_responses.staff_id', $userId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->findAll();

        return view('section_heads/my_tickets', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * View a single ticket with full details and history.
     */
    public function viewTicket(int $ticketId)
    {
        $sectionId = $this->sectionId();

        $ticket = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.*')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->where('job_ticket_requests.section_id', $sectionId)
            ->first();

        if (! $ticket) {
            return redirect()->to('admin/tickets')->with('error', 'Ticket not found.');
        }

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, users.name as staff_name')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_id', $ticketId)
            ->first();

        return view('section_heads/view_ticket', [
            'ticket'        => $ticket,
            'response'      => $response,
            'responseParts' => $response ? $this->responsePartModel->getByResponseId((int) $response['job_ticket_response_id']) : [],
            'history'       => $this->ticketHistoryModel->getByTicketId($ticketId),
        ]);
    }

    /**
     * Show the response form for an assigned ticket.
     */
    public function respondForm(int $responseId)
    {
        $userId = $this->userId();

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.*, requestor.name as requestor_name')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_responses.staff_id', $userId)
            ->first();

        if (! $response) {
            return redirect()->to('admin/my-tickets')->with('error', 'Ticket response not found or not assigned to you.');
        }

        $existingParts = $this->responsePartModel->getByResponseId($responseId);

        return view('section_heads/respond', [
            'response'      => $response,
            'existingParts' => $existingParts,
        ]);
    }

    /**
     * Process ticket response submission.
     */
    public function submitResponse(int $responseId)
    {
        $userId = $this->userId();

        // Verify ownership
        $existing = $this->jobTicketResponseModel->find($responseId);
        if (! $existing || (int) $existing['staff_id'] !== $userId) {
            return redirect()->to('admin/my-tickets')->with('error', 'Unauthorized.');
        }

        $updateData = [
            'action_performed'        => $this->request->getPost('action_performed'),
            'estimated_cost'          => $this->request->getPost('estimated_cost') ?: null,
            'completion_date'         => $this->request->getPost('completion_date') ?: null,
            'completion_status'       => $this->request->getPost('completion_status'),
        ];

        // If marking as completed, update parent ticket
        if ($this->request->getPost('completion_status') === 'completed') {
            $updateData['completion_date'] = $updateData['completion_date'] ?: date('Y-m-d');
            $completedId  = JobStatusModel::getIdByLabel('Completed');
            $inProgressId = JobStatusModel::getIdByLabel('In Progress');
            $this->jobTicketModel->update($existing['job_ticket_id'], [
                'job_status' => $completedId,
            ]);

            // Log: status changed to completed
            $this->ticketHistoryModel->log(
                (int) $existing['job_ticket_id'],
                'completed',
                $inProgressId,
                $completedId,
                $userId,
                'Marked as completed'
            );

            // Promote the next queued OPEN ticket for this staff to IN_PROGRESS
            $this->promoteNextTicket($userId);

            // Notify the requestor their ticket has been completed
            $this->sendStatusUpdateEmail(
                (int) $existing['job_ticket_id'],
                'completed',
                'Completed',
                'The technician has completed the work on your ticket. It is now pending verification by the section head.'
            );
        }

        $this->jobTicketResponseModel->update($responseId, $updateData);

        // Save parts to separate table
        $parts = $this->request->getPost('parts') ?? [];
        $this->responsePartModel->syncParts($responseId, $parts);

        return redirect()->to('admin/my-tickets')->with('success', 'Response submitted successfully!');
    }

    /**
     * Show the transfer form for a ticket response.
     * Section heads can transfer any ticket in their section.
     */
    public function transferForm(int $responseId)
    {
        $sectionId = $this->sectionId();
        $userId = $this->userId();

        // Get the response with ticket details
        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.problem_description, job_ticket_requests.section_id, assigned.name as assigned_name')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users as assigned', 'assigned.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_requests.section_id', $sectionId)
            ->first();

        if (! $response) {
            return redirect()->to('admin/tickets')->with('error', 'Ticket not found or not in your section.');
        }

        // Only allow transfer of active tickets
        if (! in_array((int) $response['job_status'], [JobStatusModel::getIdByLabel('Open'), JobStatusModel::getIdByLabel('In Progress')])) {
            return redirect()->to('admin/tickets')->with('error', 'Only active tickets can be transferred.');
        }

        // Get eligible employees in the section (excluding current assignee)
        $employees = $this->userModel
            ->whereIn('role_id', [2, 3])
            ->where('section_id', $sectionId)
            ->where('user_id !=', (int) $response['staff_id'])
            ->findAll();

        // Get active ticket counts for each employee
        foreach ($employees as &$emp) {
            $emp['active_tickets'] = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $emp['user_id'])
                ->whereIn('job_tickets.job_status', [JobStatusModel::getIdByLabel('Open'), JobStatusModel::getIdByLabel('In Progress')])
                ->countAllResults();
        }

        return view('section_heads/transfer', [
            'response'  => $response,
            'employees' => $employees,
        ]);
    }

    /**
     * Process ticket transfer.
     */
    public function transferTicket(int $responseId)
    {
        $sectionId = $this->sectionId();
        $userId = $this->userId();
        $newStaffId = (int) $this->request->getPost('new_staff_id');

        // Verify the response belongs to this section
        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_ticket_requests.section_id')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_requests.section_id', $sectionId)
            ->first();

        if (! $response) {
            return redirect()->to('admin/tickets')->with('error', 'Ticket not found.');
        }

        // Verify new staff is in the same section
        $newStaff = $this->userModel->find($newStaffId);
        if (! $newStaff || (int) $newStaff['section_id'] !== $sectionId) {
            return redirect()->to('admin/tickets')->with('error', 'Invalid employee selected.');
        }

        // Update the response with new assignee
        $this->jobTicketResponseModel->update($responseId, [
            'staff_id'       => $newStaffId,
            'transferred_by' => $userId,
            'transferred_at' => date('Y-m-d H:i:s'),
        ]);

        // Log: ticket transferred
        $this->ticketHistoryModel->log(
            (int) $response['job_ticket_id'],
            'transferred',
            null,
            null,
            $userId,
            'Transferred to ' . esc($newStaff['name'])
        );

        return redirect()->to('admin/tickets')->with('success', 'Ticket successfully transferred to ' . esc($newStaff['name']) . '.');
    }

    /**
     * Promote the next queued OPEN ticket for a staff member to IN_PROGRESS.
     * Called after a ticket is marked as completed so the staff's queue advances.
     */
    private function promoteNextTicket(int $staffId): void
    {
        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');

        // Only promote if the staff has no other IN_PROGRESS tickets
        $inProgressCount = $this->jobTicketResponseModel
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->where('job_ticket_responses.staff_id', $staffId)
            ->where('job_tickets.job_status', $inProgressId)
            ->countAllResults();

        if ($inProgressCount > 0) {
            return;
        }

        // Find the oldest OPEN ticket assigned to this staff
        $nextTicket = $this->jobTicketResponseModel
            ->select('job_ticket_responses.job_ticket_id, job_tickets.job_ticket_id as tid')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->where('job_ticket_responses.staff_id', $staffId)
            ->where('job_tickets.job_status', $openId)
            ->orderBy('job_tickets.created_at', 'ASC')
            ->first();

        if (! $nextTicket) {
            return;
        }

        $nextTicketId = (int) $nextTicket['job_ticket_id'];

        $this->jobTicketModel->update($nextTicketId, [
            'job_status' => $inProgressId,
        ]);

        $this->ticketHistoryModel->log(
            $nextTicketId,
            'in_progress',
            $openId,
            $inProgressId,
            $staffId,
            'Automatically set to In Progress (previous ticket completed)'
        );

        // Notify the requestor their ticket has moved to In Progress
        $this->sendStatusUpdateEmail(
            $nextTicketId,
            'in_progress',
            'In Progress',
            'A technician is now actively working on your ticket.'
        );
    }

    /**
     * Send a status update email to the ticket requestor.
     */
    private function sendStatusUpdateEmail(int $ticketId, string $status, string $statusLabel, ?string $updateNote = null): void
    {
        $ticket = $this->jobTicketModel
            ->select('job_tickets.job_ticket_id, job_ticket_requests.problem_description, job_ticket_requests.section_id, users.email, users.alt_email, users.name as requestor_name, users.role_id')
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

    // ═══════════════════════════════════════════════════════
    //  KEYWORD RULES CRUD (scoped to this section head's section)
    // ═══════════════════════════════════════════════════════

    public function keywordRules()
    {
        $sectionId = $this->sectionId();
        $list = $this->keywordRuleModel->getBySectionId($sectionId);

        return view('section_heads/keyword_rules', [
            'keywordRules' => $list,
            'sectionId'    => $sectionId,
        ]);
    }

    public function addKeywordRulePage()
    {
        return view('section_heads/add_keyword_rule', [
            'sectionId' => $this->sectionId(),
        ]);
    }

    public function addKeywordRule()
    {
        $sectionId = $this->sectionId();
        $isDefault = (bool) $this->request->getPost('is_default');

        $rules = [
            'keyword'   => $isDefault ? 'permit_empty|max_length[100]' : 'required|max_length[100]',
            'tip_title' => 'permit_empty|max_length[255]',
            'tip_body'  => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return view('section_heads/add_keyword_rule', [
                'sectionId'  => $sectionId,
                'validation' => $this->validator,
            ]);
        }

        $keyword = $isDefault ? '_default' : strtolower(trim($this->request->getPost('keyword')));

        $this->keywordRuleModel->insert([
            'section_id' => $sectionId,
            'keyword'    => $keyword,
            'tip_title'  => trim($this->request->getPost('tip_title') ?? ''),
            'tip_body'   => trim($this->request->getPost('tip_body') ?? ''),
            'is_default' => $isDefault ? 1 : 0,
            'is_active'  => 1,
        ]);

        return redirect()->to('admin/keyword-rules')->with('success', 'Keyword rule added successfully.');
    }

    public function editKeywordRulePage(int $id)
    {
        $item = $this->keywordRuleModel->find($id);
        if (! $item || (int) $item['section_id'] !== $this->sectionId()) {
            return redirect()->to('admin/keyword-rules')->with('error', 'Keyword rule not found.');
        }

        return view('section_heads/edit_keyword_rule', [
            'rule'      => $item,
            'sectionId' => $this->sectionId(),
        ]);
    }

    public function updateKeywordRule(int $id)
    {
        $sectionId = $this->sectionId();
        $item = $this->keywordRuleModel->find($id);
        if (! $item || (int) $item['section_id'] !== $sectionId) {
            return redirect()->to('admin/keyword-rules')->with('error', 'Keyword rule not found.');
        }

        $isDefault = (bool) $this->request->getPost('is_default');

        $rules = [
            'keyword'   => $isDefault ? 'permit_empty|max_length[100]' : 'required|max_length[100]',
            'tip_title' => 'permit_empty|max_length[255]',
            'tip_body'  => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return view('section_heads/edit_keyword_rule', [
                'rule'       => $item,
                'sectionId'  => $sectionId,
                'validation' => $this->validator,
            ]);
        }

        $keyword = $isDefault ? '_default' : strtolower(trim($this->request->getPost('keyword')));

        $this->keywordRuleModel->update($id, [
            'keyword'    => $keyword,
            'tip_title'  => trim($this->request->getPost('tip_title') ?? ''),
            'tip_body'   => trim($this->request->getPost('tip_body') ?? ''),
            'is_default' => $isDefault ? 1 : 0,
            'is_active'  => (int) ($this->request->getPost('is_active') ?? 1),
        ]);

        return redirect()->to('admin/keyword-rules/edit/' . $id)->with('success', 'Keyword rule updated successfully.');
    }

    public function deleteKeywordRule(int $id)
    {
        $item = $this->keywordRuleModel->find($id);
        if (! $item || (int) $item['section_id'] !== $this->sectionId()) {
            return redirect()->to('admin/keyword-rules')->with('error', 'Keyword rule not found.');
        }

        $this->keywordRuleModel->delete($id);
        return redirect()->to('admin/keyword-rules')->with('success', '"' . $item['keyword'] . '" has been deleted.');
    }
}
