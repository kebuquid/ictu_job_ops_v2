<?php

namespace App\Models;

use CodeIgniter\Model;

class JobStatusModel extends Model
{
    protected $table            = 'job_status';
    protected $primaryKey       = 'status_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['label', 'color', 'dot_color', 'activity_label'];

    private static ?array $cachedStatuses = null;

    public static function getAllCached(): array
    {
        if (self::$cachedStatuses === null) {
            self::$cachedStatuses = (new self())->orderBy('status_id')->findAll();
        }
        return self::$cachedStatuses;
    }

    public static function getById(int $id): ?array
    {
        foreach (self::getAllCached() as $status) {
            if ((int) $status['status_id'] === $id) {
                return $status;
            }
        }
        return null;
    }

    public static function getIdByLabel(string $label): int
    {
        foreach (self::getAllCached() as $status) {
            if ($status['label'] === $label) {
                return (int) $status['status_id'];
            }
        }
        return 0;
    }

    public static function badge(int $statusId): string
    {
        $status = self::getById($statusId);
        if (!$status) {
            return '';
        }
        $color = $status['color'];
        return '<span class="text-xs font-bold px-2 py-1 rounded-full bg-' . $color . '-100 text-' . $color . '-700">' . esc($status['label']) . '</span>';
    }

    public static function badgeMd(int $statusId): string
    {
        $status = self::getById($statusId);
        if (!$status) {
            return '';
        }
        $color = $status['color'];
        return '<span class="text-sm font-bold px-3 py-1.5 rounded-full bg-' . $color . '-100 text-' . $color . '-700">' . esc($status['label']) . '</span>';
    }

    public static function activityLabel(int $statusId): string
    {
        $status = self::getById($statusId);
        return $status['activity_label'] ?? '';
    }

    public static function dotColor(int $statusId): string
    {
        $status = self::getById($statusId);
        return $status['dot_color'] ?? '';
    }

    public static function color(int $statusId): string
    {
        $status = self::getById($statusId);
        if (!$status) {
            return '';
        }
        $color = $status['color'];
        return 'bg-' . $color . '-100 text-' . $color . '-700';
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::getAllCached() as $status) {
            $options[(int) $status['status_id']] = $status['label'];
        }
        return $options;
    }
}
