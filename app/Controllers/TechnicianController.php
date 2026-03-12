<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\ResponsePartModel;
use App\Models\SectionModel;
use App\Models\UserModel;
use App\Models\TicketHistoryModel;
use App\Models\JobStatusModel;

class TechnicianController extends BaseController
{
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private ResponsePartModel $responsePartModel;
    private SectionModel $sectionModel;
    private UserModel $userModel;
    private TicketHistoryModel $ticketHistoryModel;

    public function __construct()
    {
        $this->jobTicketModel         = new JobTicketModel();
        $this->jobTicketRequestModel  = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->responsePartModel      = new ResponsePartModel();
        $this->sectionModel           = new SectionModel();
        $this->userModel              = new UserModel();
        $this->ticketHistoryModel     = new TicketHistoryModel();
    }

    private function userId(): int
    {
        return (int) (session()->get('user')['user_id'] ?? 0);
    }

    /**
     * Determine which layout folder to use based on user role.
     */
    private function viewFolder(): string
    {
        return 'ictu-staff';
    }

    /**
     * Determine URL prefix based on role.
     */
    private function urlPrefix(): string
    {
        return 'ictu-staff';
    }

    /**
     * Dashboard – personal ticket stats + active list.
     */
    public function dashboard()
    {
        $userId = $this->userId();

        // My response tickets joined with parent ticket status
        $myResponses = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->where('job_ticket_responses.staff_id', $userId)
            ->findAll();

        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
        $completedId  = JobStatusModel::getIdByLabel('Completed');
        $closedId     = JobStatusModel::getIdByLabel('Closed');

        $stats = ['total' => count($myResponses), 'active' => 0, 'completed' => 0, 'needs_response' => 0];
        foreach ($myResponses as $r) {
            $status = (int) $r['job_status'];
            if (in_array($status, [$openId, $inProgressId])) {
                $stats['active']++;
            }
            if ($status === $completedId || $status === $closedId) {
                $stats['completed']++;
            }
            // Needs response: active tickets where action_performed is still null
            if (in_array($status, [$openId, $inProgressId]) && empty($r['action_performed'])) {
                $stats['needs_response']++;
            }
        }

        // Active tickets for dashboard table
        $activeTickets = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_tickets.created_at, job_ticket_requests.problem_description')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_responses.staff_id', $userId)
            ->whereIn('job_tickets.job_status', [$openId, $inProgressId])
            ->orderBy('job_tickets.created_at', 'DESC')
            ->limit(8)
            ->findAll();

        return view($this->viewFolder() . '/dashboard', [
            'stats'         => $stats,
            'activeTickets' => $activeTickets,
        ]);
    }

    /**
     * All my assigned tickets.
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

        return view($this->viewFolder() . '/my_tickets', [
            'tickets'   => $tickets,
            'urlPrefix' => $this->urlPrefix(),
        ]);
    }

    /**
     * View a single ticket with full details and history.
     */
    public function viewTicket(int $ticketId)
    {
        $userId = $this->userId();

        $ticket = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.*')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->where('job_ticket_responses.staff_id', $userId)
            ->first();

        if (! $ticket) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket not found.');
        }

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, users.name as staff_name')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_id', $ticketId)
            ->first();

        return view($this->viewFolder() . '/view_ticket', [
            'ticket'        => $ticket,
            'response'      => $response,
            'responseParts' => $response ? $this->responsePartModel->getByResponseId((int) $response['job_ticket_response_id']) : [],
            'history'       => $this->ticketHistoryModel->getByTicketId($ticketId),
            'urlPrefix'     => $this->urlPrefix(),
        ]);
    }

    /**
     * Show the response form for a ticket.
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
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket response not found or not assigned to you.');
        }

        $existingParts = $this->responsePartModel->getByResponseId($responseId);

        return view($this->viewFolder() . '/respond', [
            'response'      => $response,
            'existingParts' => $existingParts,
            'urlPrefix'     => $this->urlPrefix(),
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
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Unauthorized.');
        }

        $updateData = [
            'action_performed'  => $this->request->getPost('action_performed'),
            'estimated_cost'    => $this->request->getPost('estimated_cost') ?: null,
            'completion_date'   => $this->request->getPost('completion_date') ?: null,
            'completion_status' => $this->request->getPost('completion_status'),
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

        return redirect()->to($this->urlPrefix() . '/my-tickets')->with('success', 'Response submitted successfully!');
    }

    /**
     * Show the transfer form for an assigned ticket.
     * Employees can transfer their own assigned tickets.
     */
    public function transferForm(int $responseId)
    {
        $userId = $this->userId();
        $user = session()->get('user');
        $sectionId = (int) ($user['section_id'] ?? 0);

        // Verify ownership
        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.problem_description, job_ticket_requests.section_id')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_responses.staff_id', $userId)
            ->first();

        if (! $response) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket not found or not assigned to you.');
        }

        // Only allow transfer of active tickets
        if (! in_array((int) $response['job_status'], [JobStatusModel::getIdByLabel('Open'), JobStatusModel::getIdByLabel('In Progress')])) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Only active tickets can be transferred.');
        }

        // Get eligible employees in the same section (excluding self)
        $employees = $this->userModel
            ->whereIn('role_id', [2, 3])
            ->where('section_id', $sectionId)
            ->where('user_id !=', $userId)
            ->findAll();

        foreach ($employees as &$emp) {
            $emp['active_tickets'] = $this->jobTicketResponseModel
                ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
                ->where('job_ticket_responses.staff_id', $emp['user_id'])
                ->whereIn('job_tickets.job_status', [JobStatusModel::getIdByLabel('Open'), JobStatusModel::getIdByLabel('In Progress')])
                ->countAllResults();
        }

        return view($this->viewFolder() . '/transfer', [
            'response'  => $response,
            'employees' => $employees,
            'urlPrefix' => $this->urlPrefix(),
        ]);
    }

    /**
     * Process ticket transfer.
     */
    public function transferTicket(int $responseId)
    {
        $userId = $this->userId();
        $user = session()->get('user');
        $sectionId = (int) ($user['section_id'] ?? 0);
        $newStaffId = (int) $this->request->getPost('new_staff_id');

        // Verify ownership
        $existing = $this->jobTicketResponseModel->find($responseId);
        if (! $existing || (int) $existing['staff_id'] !== $userId) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Unauthorized.');
        }

        // Verify new staff is in the same section
        $newStaff = $this->userModel->find($newStaffId);
        if (! $newStaff || (int) $newStaff['section_id'] !== $sectionId) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Invalid employee selected.');
        }

        // Update the response with new assignee
        $this->jobTicketResponseModel->update($responseId, [
            'staff_id'       => $newStaffId,
            'transferred_by' => $userId,
            'transferred_at' => date('Y-m-d H:i:s'),
        ]);

        // Log: ticket transferred
        $this->ticketHistoryModel->log(
            (int) $existing['job_ticket_id'],
            'transferred',
            null,
            null,
            $userId,
            'Transferred to ' . esc($newStaff['name'])
        );

        return redirect()->to($this->urlPrefix() . '/my-tickets')->with('success', 'Ticket successfully transferred to ' . esc($newStaff['name']) . '.');
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
}
