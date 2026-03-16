<?php

namespace App\Libraries;

use App\Models\JobStatusModel;
use App\Models\TicketSlaRuleModel;

class TicketSlaResolver
{
    private TicketSlaRuleModel $ruleModel;

    public function __construct()
    {
        $this->ruleModel = new TicketSlaRuleModel();
    }

    public function resolveForTicket(array $ticket, ?array $response): ?array
    {
        $sectionId = (int) ($ticket['section_id'] ?? 0);
        if ($sectionId <= 0) {
            return null;
        }

        $statusInProgressId = JobStatusModel::getIdByLabel('In Progress');
        $isInProgress = (int) ($ticket['job_status'] ?? 0) === $statusInProgressId;

        $startDate = $response['start_date'] ?? null;
        if (empty($startDate) || ! $isInProgress) {
            return null;
        }

        $requestType = $this->toNullableInt($ticket['request_type'] ?? null);
        $platform    = $this->toNullableInt($ticket['request_platform'] ?? null);
        $action      = $this->toNullableInt($ticket['request_action'] ?? null);
        $equipment   = $this->toNullableInt($ticket['request_equipment'] ?? null);

        $rules = $this->ruleModel
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->findAll();

        if (empty($rules)) {
            return null;
        }

        $best = null;
        $bestScore = -1;

        foreach ($rules as $rule) {
            $score = 0;

            if (! $this->matchesRuleField($rule['request_type_id'] ?? null, $requestType, $score)) {
                continue;
            }
            if (! $this->matchesRuleField($rule['platform_id'] ?? null, $platform, $score)) {
                continue;
            }
            if (! $this->matchesRuleField($rule['action_id'] ?? null, $action, $score)) {
                continue;
            }
            if (! $this->matchesRuleField($rule['equipment_id'] ?? null, $equipment, $score)) {
                continue;
            }

            if ($score > $bestScore) {
                $best = $rule;
                $bestScore = $score;
            }
        }

        if (! $best) {
            return null;
        }

        $targetHours = (int) ($best['target_hours'] ?? 0);
        if ($targetHours <= 0) {
            return null;
        }

        $startedAt = date('Y-m-d H:i:s', strtotime($startDate . ' 00:00:00'));
        $dueAt = date('Y-m-d H:i:s', strtotime($startedAt . ' +' . $targetHours . ' hours'));

        $remaining = strtotime($dueAt) - time();

        return [
            'rule'              => $best,
            'started_at'        => $startedAt,
            'due_at'            => $dueAt,
            'target_hours'      => $targetHours,
            'remaining_seconds' => $remaining,
            'is_overdue'        => $remaining < 0,
        ];
    }

    private function toNullableInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $num = (int) $value;
        return $num > 0 ? $num : null;
    }

    private function matchesRuleField($ruleValue, ?int $ticketValue, int &$score): bool
    {
        if ($ruleValue === null || $ruleValue === '') {
            return true;
        }

        $rv = (int) $ruleValue;
        if ($rv <= 0) {
            return true;
        }

        if ($ticketValue === null) {
            return false;
        }

        if ($rv !== $ticketValue) {
            return false;
        }

        $score++;
        return true;
    }
}
