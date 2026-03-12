<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JobTicketModel;
use App\Models\JobTicketRequestModel;
use App\Models\JobTicketResponseModel;
use App\Models\ResponsePartModel;
use App\Models\TicketHistoryModel;
use App\Models\JobStatusModel;

class EmployeeDashboardController extends BaseController
{
    private JobTicketModel $jobTicketModel;
    private JobTicketRequestModel $jobTicketRequestModel;
    private JobTicketResponseModel $jobTicketResponseModel;
    private ResponsePartModel $responsePartModel;
    private TicketHistoryModel $ticketHistoryModel;

    public function __construct()
    {
        $this->jobTicketModel         = new JobTicketModel();
        $this->jobTicketRequestModel  = new JobTicketRequestModel();
        $this->jobTicketResponseModel = new JobTicketResponseModel();
        $this->responsePartModel      = new ResponsePartModel();
        $this->ticketHistoryModel     = new TicketHistoryModel();
    }

    private function userId(): int
    {
        return (int) (session()->get('user')['user_id'] ?? 0);
    }

    /**
     * Employee dashboard – see my submitted tickets.
     */
    public function dashboard()
    {
        $userId = $this->userId();

        $myTickets = $this->jobTicketModel
            ->where('requestor_id', $userId)
            ->findAll();

        $openId       = JobStatusModel::getIdByLabel('Open');
        $inProgressId = JobStatusModel::getIdByLabel('In Progress');
        $completedId  = JobStatusModel::getIdByLabel('Completed');
        $closedId     = JobStatusModel::getIdByLabel('Closed');

        $stats = ['total' => count($myTickets), 'open' => 0, 'in_progress' => 0, 'resolved' => 0];
        foreach ($myTickets as $t) {
            match ((int) $t['job_status']) {
                $openId       => $stats['open']++,
                $inProgressId => $stats['in_progress']++,
                $completedId, $closedId => $stats['resolved']++,
                default => null,
            };
        }

        // Recent tickets with extra details
        $recentTickets = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.problem_description, users.name as staff_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_tickets.requestor_id', $userId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return view('employees/dashboard', [
            'stats'         => $stats,
            'recentTickets' => $recentTickets,
        ]);
    }

    /**
     * Full list of my submitted tickets.
     */
    public function myTickets()
    {
        $userId = $this->userId();

        $tickets = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.problem_description, job_ticket_requests.priority_level, users.name as staff_name')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('job_ticket_responses', 'job_ticket_responses.job_ticket_id = job_tickets.job_ticket_id', 'left')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_tickets.requestor_id', $userId)
            ->orderBy('job_tickets.created_at', 'DESC')
            ->findAll();

        return view('employees/my_tickets', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * View single ticket detail with response info.
     */
    public function viewTicket(int $ticketId)
    {
        $userId = $this->userId();

        $ticket = $this->jobTicketModel
            ->select('job_tickets.*, job_ticket_requests.*')
            ->join('job_ticket_requests', 'job_ticket_requests.job_ticket_id = job_tickets.job_ticket_id')
            ->where('job_tickets.job_ticket_id', $ticketId)
            ->where('job_tickets.requestor_id', $userId)
            ->first();

        if (! $ticket) {
            return redirect()->to('employee/my-tickets')->with('error', 'Ticket not found.');
        }

        $response = $this->jobTicketResponseModel
            ->select('job_ticket_responses.*, users.name as staff_name')
            ->join('users', 'users.user_id = job_ticket_responses.staff_id', 'left')
            ->where('job_ticket_responses.job_ticket_id', $ticketId)
            ->first();

        return view('employees/view_ticket', [
            'ticket'        => $ticket,
            'response'      => $response,
            'responseParts' => $response ? $this->responsePartModel->getByResponseId((int) $response['job_ticket_response_id']) : [],
            'history'       => $this->ticketHistoryModel->getByTicketId($ticketId),
        ]);
    }
}
