<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'role_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['access', 'label', 'url_path', 'role_color'];

    private static ?array $cachedRoles = null;

    public static function getAllCached(): array
    {
        if (self::$cachedRoles === null) {
            self::$cachedRoles = (new self())->orderBy('role_id')->findAll();
        }
        return self::$cachedRoles;
    }

    public static function getById(int $id): ?array
    {
        foreach (self::getAllCached() as $role) {
            if ((int) $role['role_id'] === $id) {
                return $role;
            }
        }
        return null;
    }

    public static function getByAccess(string $access): ?array
    {
        foreach (self::getAllCached() as $role) {
            if ($role['access'] === $access) {
                return $role;
            }
        }
        return null;
    }

    public static function getUrlPath(int $roleId): string
    {
        $role = self::getById($roleId);
        return $role['url_path'] ?? '';
    }

    public static function getRoleColor(int $roleId): string
    {
        $role = self::getById($roleId);
        return $role['role_color'] ?? '';
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::getAllCached() as $role) {
            $options[(int) $role['role_id']] = $role['label'];
        }
        return $options;
    }

    public static function isAdmin(int $roleId): bool
    {
        return in_array($roleId, [1, 2]);
    }
}
