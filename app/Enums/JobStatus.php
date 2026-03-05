<?php

namespace App\Enums;

enum JobStatus: int
{
    case OPEN = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 3;
    case CLOSED = 4;
    case CANCELLED = 5;

    /**
     * Get the human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::OPEN        => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED   => 'Completed',
            self::CLOSED      => 'Closed',
            self::CANCELLED   => 'Cancelled',
        };
    }

    /**
     * Get the Tailwind badge color classes
     */
    public function color(): string
    {
        return match($this) {
            self::OPEN        => 'bg-amber-100 text-amber-700',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-700',
            self::COMPLETED   => 'bg-emerald-100 text-emerald-700',
            self::CLOSED      => 'bg-gray-100 text-gray-700',
            self::CANCELLED   => 'bg-red-100 text-red-700',
        };
    }

    /**
     * Get the Tailwind dot/icon color class
     */
    public function dotColor(): string
    {
        return match($this) {
            self::OPEN        => 'bg-amber-500',
            self::IN_PROGRESS => 'bg-blue-500',
            self::COMPLETED   => 'bg-emerald-500',
            self::CLOSED      => 'bg-gray-500',
            self::CANCELLED   => 'bg-red-500',
        };
    }

    /**
     * Get a short activity label (for history/timeline)
     */
    public function activityLabel(): string
    {
        return match($this) {
            self::OPEN        => 'opened',
            self::IN_PROGRESS => 'moved to In Progress',
            self::COMPLETED   => 'marked Completed',
            self::CLOSED      => 'was Closed',
            self::CANCELLED   => 'was Cancelled',
        };
    }

    /**
     * Render an inline badge HTML (small)
     */
    public function badge(): string
    {
        return '<span class="text-xs font-bold px-2 py-1 rounded-full ' . $this->color() . '">' . $this->label() . '</span>';
    }

    /**
     * Render an inline badge HTML (medium, for detail views)
     */
    public function badgeMd(): string
    {
        return '<span class="text-sm font-bold px-3 py-1.5 rounded-full ' . $this->color() . '">' . $this->label() . '</span>';
    }

    /**
     * Get all statuses as value array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get statuses for dropdown
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }
        return $options;
    }
}