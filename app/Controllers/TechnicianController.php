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
use App\Models\TicketTransferRequestModel;
use App\Models\AssetModel;
use App\Models\IssueTypeModel;
use App\Models\RequestTypeModel;
use App\Models\RequestPlatformModel;
use App\Models\RequestActionModel;
use App\Models\TicketEquipmentModel;
use App\Libraries\TicketSlaResolver;

class TechnicianController extends BaseController
{
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private ResponsePartModel $responsePartModel;
    private SectionModel $sectionModel;
    private UserModel $userModel;
    private TicketHistoryModel $ticketHistoryModel;
    private TicketTransferRequestModel $transferRequestModel;
    private TicketSlaResolver $ticketSlaResolver;
    private AssetModel $assetModel;
    private IssueTypeModel $issueTypeModel;
    private RequestTypeModel $requestTypeModel;
    private RequestPlatformModel $requestPlatformModel;
    private RequestActionModel $requestActionModel;
    private TicketEquipmentModel $ticketEquipmentModel;

    public function __construct()
    {
        $this->jobTicketModel         = new JobTicketModel();
        $this->jobTicketRequestModel  = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->responsePartModel      = new ResponsePartModel();
        $this->sectionModel           = new SectionModel();
        $this->userModel              = new UserModel();
        $this->ticketHistoryModel     = new TicketHistoryModel();
        $this->transferRequestModel   = new TicketTransferRequestModel();
        $this->ticketSlaResolver      = new TicketSlaResolver();
        $this->assetModel             = new AssetModel();
        $this->issueTypeModel         = new IssueTypeModel();
        $this->requestTypeModel       = new RequestTypeModel();
        $this->requestPlatformModel   = new RequestPlatformModel();
        $this->requestActionModel     = new RequestActionModel();
        $this->ticketEquipmentModel   = new TicketEquipmentModel();
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
     * All tickets in my section.
     */
    public function myTickets()
    {
        $userId = $this->userId();
        $sectionId = (int) (session()->get('user')['section_id'] ?? 0);

        $openId = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');

        $tickets = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_tickets.created_at as ticket_date, job_ticket_requests.problem_description, job_ticket_requests.priority_level, requestor.name as requestor_name, assigned.name as assigned_name')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->join('users as assigned', 'assigned.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_requests.section_id', $sectionId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->findAll();

        $responseIds = array_values(array_filter(array_map(static fn($ticket) => (int) ($ticket['job_ticket_response_id'] ?? 0), $tickets)));
        $latestTransferRequestByResponse = [];

        if (! empty($responseIds)) {
            $transferRequests = $this->transferRequestModel
                ->select('ticket_transfer_requests.*, requested.name as requested_by_name, suggested.name as suggested_staff_name')
                ->join('users as requested', 'requested.user_id = ticket_transfer_requests.requested_by', 'left')
                ->join('users as suggested', 'suggested.user_id = ticket_transfer_requests.suggested_staff_id', 'left')
                ->whereIn('job_ticket_response_id', $responseIds)
                ->orderBy('transfer_request_id', 'DESC')
                ->findAll();

            foreach ($transferRequests as $request) {
                $responseId = (int) $request['job_ticket_response_id'];
                if (! isset($latestTransferRequestByResponse[$responseId])) {
                    $latestTransferRequestByResponse[$responseId] = $request;
                }
            }
        }

        foreach ($tickets as &$ticket) {
            $responseId = (int) ($ticket['job_ticket_response_id'] ?? 0);
            $latestRequest = $latestTransferRequestByResponse[$responseId] ?? null;

            $ticket['latest_transfer_request'] = $latestRequest;
            $ticket['can_take'] = ((int) $ticket['job_status'] === $openId)
                && ((int) ($ticket['staff_id'] ?? 0) !== $userId);
            $ticket['can_respond'] = ((int) ($ticket['staff_id'] ?? 0) === $userId)
                && in_array((int) $ticket['job_status'], [1, 2, 3], true);
            $ticket['can_request_transfer'] = ((int) ($ticket['staff_id'] ?? 0) === $userId)
                && in_array((int) $ticket['job_status'], [$openId, $inProgressId], true);
            $ticket['has_pending_transfer_request'] = ! empty($latestRequest)
                && ($latestRequest['status'] ?? '') === 'pending';
        }
        unset($ticket);

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
        $sectionId = (int) (session()->get('user')['section_id'] ?? 0);

        $ticket = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.*, requestor.name as requestor_name, requestor.email as requestor_email, requestor.account_no as requestor_account_no, requestor.phone_number as requestor_phone_number')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->where('job_ticket_requests.section_id', $sectionId)
            ->first();

        if (! $ticket) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket not found.');
        }

        $asset = null;
        if (! empty($ticket['asset_id'])) {
            $asset = $this->assetModel->find($ticket['asset_id']);
        }

        $ticket['hardware_issues_text'] = $this->resolveIssueTypes($ticket['hardware_issues'] ?? '');
        $ticket['software_issues_text'] = $this->resolveIssueTypes($ticket['software_issues'] ?? '');

        // Resolve other request details if they are numeric IDs
        $ticket['request_type']      = $this->resolveRequestType($ticket['request_type'] ?? '');
        $ticket['request_platform']  = $this->resolveRequestPlatform($ticket['request_platform'] ?? '');
        $ticket['request_action']    = $this->resolveRequestAction($ticket['request_action'] ?? '');
        $ticket['request_equipment'] = $this->resolveTicketEquipment($ticket['request_equipment'] ?? '');

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, users.name as staff_name')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_id', $ticketId)
            ->first();

        $slaSummary = $this->ticketSlaResolver->resolveForTicket($ticket, $response);

        return view($this->viewFolder() . '/view_ticket', [
            'ticket'        => $ticket,
            'asset'         => $asset,
            'response'      => $response,
            'responseParts' => $response ? $this->responsePartModel->getByResponseId((int) $response['job_ticket_response_id']) : [],
            'history'       => $this->ticketHistoryModel->getByTicketId($ticketId),
            'urlPrefix'     => $this->urlPrefix(),
            'slaSummary'    => $slaSummary,
        ]);
    }

    /**
     * Show the response form for a ticket.
     */
    public function respondForm(int $responseId)
    {
        $userId = $this->userId();

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.*, requestor.name as requestor_name, requestor.email as requestor_email, requestor.account_no as requestor_account_no, requestor.phone_number as requestor_phone_number')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->join('users as requestor', 'requestor.user_id = job_tickets.requestor_id', 'left')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_responses.staff_id', $userId)
            ->first();

        if (! $response) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket response not found or not assigned to you.');
        }

        $asset = null;
        if (! empty($response['asset_id'])) {
            $asset = $this->assetModel->find($response['asset_id']);
        }

        $response['hardware_issues_text'] = $this->resolveIssueTypes($response['hardware_issues'] ?? '');
        $response['software_issues_text'] = $this->resolveIssueTypes($response['software_issues'] ?? '');

        // Resolve other request details if they are numeric IDs
        $response['request_type']      = $this->resolveRequestType($response['request_type'] ?? '');
        $response['request_platform']  = $this->resolveRequestPlatform($response['request_platform'] ?? '');
        $response['request_action']    = $this->resolveRequestAction($response['request_action'] ?? '');
        $response['request_equipment'] = $this->resolveTicketEquipment($response['request_equipment'] ?? '');

        $existingParts = $this->responsePartModel->getByResponseId($responseId);

        return view($this->viewFolder() . '/respond', [
            'response'      => $response,
            'asset'         => $asset,
            'existingParts' => $existingParts,
            'urlPrefix'     => $this->urlPrefix(),
        ]);
    }

    private function resolveIssueTypes(string $ids): string
    {
        if (empty($ids)) {
            return '';
        }

        $idArray = explode(',', $ids);
        $resolvedNames = [];

        foreach ($idArray as $id) {
            $id = trim($id);
            if (is_numeric($id)) {
                $issue = $this->issueTypeModel->find($id);
                if ($issue) {
                    $resolvedNames[] = $issue['issue_type_name'];
                } else {
                    $resolvedNames[] = $id;
                }
            } else {
                $resolvedNames[] = $id;
            }
        }

        return implode(', ', $resolvedNames);
    }

    private function resolveRequestType($id)
    {
        if (is_numeric($id)) {
            $item = $this->requestTypeModel->find($id);
            return $item ? $item['request_type_name'] : $id;
        }
        return $id;
    }

    private function resolveRequestPlatform($id)
    {
        if (is_numeric($id)) {
            $item = $this->requestPlatformModel->find($id);
            return $item ? $item['platform_name'] : $id;
        }
        return $id;
    }

    private function resolveRequestAction($id)
    {
        if (is_numeric($id)) {
            $item = $this->requestActionModel->find($id);
            return $item ? $item['action_name'] : $id;
        }
        return $id;
    }

    private function resolveTicketEquipment($id)
    {
        if (is_numeric($id)) {
            $item = $this->ticketEquipmentModel->find($id);
            return $item ? $item['name'] : $id;
        }
        return $id;
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

            $ticketForSla = $this->jobTicketModel
                ->select('job_tickets.job_status, job_ticket_requests.section_id, job_ticket_requests.request_type, job_ticket_requests.request_platform, job_ticket_requests.request_action, job_ticket_requests.request_equipment')
                ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
                ->where('job_tickets.job_ticket_id', (int) $existing['job_ticket_id'])
                ->first();

            $slaSummary = $ticketForSla ? $this->ticketSlaResolver->resolveForTicket($ticketForSla, $existing) : null;
            if ($slaSummary && ! empty($slaSummary['due_at'])) {
                $completionTs = strtotime($updateData['completion_date'] . ' 23:59:59');
                $dueTs        = strtotime((string) $slaSummary['due_at']);
                $updateData['is_completed_in_timeline'] = ($completionTs <= $dueTs) ? 1 : 0;
            }

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
     * Show transfer request form for an assigned ticket.
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

        $pendingRequest = $this->transferRequestModel
            ->select('ticket_transfer_requests.*, suggested.name as suggested_staff_name')
            ->join('users as suggested', 'suggested.user_id = ticket_transfer_requests.suggested_staff_id', 'left')
            ->where('job_ticket_response_id', $responseId)
            ->where('requested_by', $userId)
            ->where('status', 'pending')
            ->orderBy('transfer_request_id', 'DESC')
            ->first();

        return view($this->viewFolder() . '/transfer', [
            'response'       => $response,
            'employees'      => $employees,
            'pendingRequest' => $pendingRequest,
            'urlPrefix'      => $this->urlPrefix(),
        ]);
    }

    /**
     * Submit transfer request for section head review.
     */
    public function transferTicket(int $responseId)
    {
        $userId = $this->userId();
        $user = session()->get('user');
        $sectionId = (int) ($user['section_id'] ?? 0);
        $newStaffRaw = trim((string) $this->request->getPost('new_staff_id'));
        $newStaffId = ctype_digit($newStaffRaw) ? (int) $newStaffRaw : 0;
        $reason = trim((string) $this->request->getPost('reason'));

        // Verify ownership
        $existing = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.section_id')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_responses.staff_id', $userId)
            ->first();

        if (! $existing || (int) $existing['section_id'] !== $sectionId) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Unauthorized.');
        }

        $activeStatuses = [
            JobStatusModel::getIdByLabel('Open'),
            JobStatusModel::getIdByLabel('In Progress'),
        ];

        if (! in_array((int) $existing['job_status'], $activeStatuses, true)) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Only active tickets can be requested for transfer.');
        }

        if ($reason === '') {
            return redirect()->back()->withInput()->with('error', 'Transfer reason is required.');
        }

        $existingPending = $this->transferRequestModel
            ->where('job_ticket_response_id', $responseId)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'A transfer request is already pending for this ticket.');
        }

        $suggestedStaffName = null;
        if ($newStaffId > 0) {
            $newStaff = $this->userModel->find($newStaffId);
            if (! $newStaff || (int) $newStaff['section_id'] !== $sectionId || (int) $newStaff['user_id'] === $userId) {
                return redirect()->back()->withInput()->with('error', 'Invalid suggested assignee selected.');
            }
            $suggestedStaffName = (string) ($newStaff['name'] ?? '');
        }

        $this->transferRequestModel->insert([
            'job_ticket_response_id' => $responseId,
            'job_ticket_id'          => (int) $existing['job_ticket_id'],
            'requested_by'           => $userId,
            'suggested_staff_id'     => $newStaffId > 0 ? $newStaffId : null,
            'reason'                 => $reason,
            'status'                 => 'pending',
        ]);

        $remarks = 'Transfer requested for section head approval.';
        if ($suggestedStaffName !== null && $suggestedStaffName !== '') {
            $remarks .= ' Suggested assignee: ' . $suggestedStaffName . '.';
        }

        $this->ticketHistoryModel->log(
            (int) $existing['job_ticket_id'],
            'transfer_requested',
            null,
            null,
            $userId,
            $remarks
        );

        return redirect()->to($this->urlPrefix() . '/my-tickets')->with('success', 'Transfer request submitted. The section head will review it.');
    }

    /**
     * Take an OPEN ticket from the section queue.
     */
    public function takeTicket(int $responseId)
    {
        $userId = $this->userId();
        $user = session()->get('user');
        $sectionId = (int) ($user['section_id'] ?? 0);

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, job_tickets.job_status, job_ticket_requests.section_id')
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_ticket_responses.job_ticket_response_id', $responseId)
            ->where('job_ticket_requests.section_id', $sectionId)
            ->first();

        if (! $response) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Ticket not found in your section.');
        }

        $openId = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');

        if ((int) $response['job_status'] !== $openId) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('error', 'Only OPEN tickets can be taken.');
        }

        if ((int) $response['staff_id'] === $userId) {
            return redirect()->to($this->urlPrefix() . '/my-tickets')->with('success', 'This ticket is already assigned to you.');
        }

        $hasInProgress = $this->jobTicketResponseModel
            ->join('job_tickets', 'job_tickets.job_ticket_id = job_ticket_responses.job_ticket_id')
            ->where('job_ticket_responses.staff_id', $userId)
            ->where('job_tickets.job_status', $inProgressId)
            ->where('job_tickets.job_ticket_id !=', (int) $response['job_ticket_id'])
            ->countAllResults();

        $newStatus = $hasInProgress > 0 ? $openId : $inProgressId;

        $this->jobTicketResponseModel->update($responseId, [
            'staff_id'       => $userId,
            'transferred_by' => $userId,
            'transferred_at' => date('Y-m-d H:i:s'),
            'start_date'     => $newStatus === $inProgressId ? date('Y-m-d') : null,
        ]);

        $this->jobTicketModel->update((int) $response['job_ticket_id'], [
            'job_status' => $newStatus,
        ]);

        // Cancel stale pending transfer requests once ownership changes.
        $this->transferRequestModel
            ->where('job_ticket_response_id', $responseId)
            ->where('status', 'pending')
            ->set([
                'status'      => 'cancelled',
                'reviewed_by' => $userId,
                'reviewed_at' => date('Y-m-d H:i:s'),
                'review_note' => 'Cancelled automatically because ticket was taken from section queue.',
            ])
            ->update();

        $this->ticketHistoryModel->log(
            (int) $response['job_ticket_id'],
            'assigned',
            $openId,
            $newStatus,
            $userId,
            'Taken from section queue by ' . ($user['name'] ?? ('Staff #' . $userId))
        );

        if ($newStatus === $inProgressId) {
            $this->ticketHistoryModel->log(
                (int) $response['job_ticket_id'],
                'in_progress',
                $openId,
                $inProgressId,
                $userId,
                'Ticket taken from queue and set to In Progress'
            );

            $this->sendStatusUpdateEmail(
                (int) $response['job_ticket_id'],
                'in_progress',
                'In Progress',
                'A technician has taken your ticket and is now actively working on it.'
            );
        }

        $message = $newStatus === $inProgressId
            ? 'Ticket taken successfully and moved to In Progress.'
            : 'Ticket taken successfully and added to your queue.';

        return redirect()->to($this->urlPrefix() . '/my-tickets')->with('success', $message);
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

        $this->jobTicketResponseModel
            ->where('job_ticket_id', $nextTicketId)
            ->where('staff_id', $staffId)
            ->set(['start_date' => date('Y-m-d')])
            ->update();

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
