<?php

namespace App\Enums;

enum UserRole: int
{
    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case ICTU_STAFF = 3;
    case EMPLOYEE = 4;
    case STUDENT = 5;

    /**
     * Get the role name
     */
    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Head',
            self::ADMIN => 'Head of Section',
            self::ICTU_STAFF => 'ICTU Staff',
            self::EMPLOYEE => 'Employee',
            self::STUDENT => 'Student',
        };
    }

    public function url_path(): string
    {
        return match($this) {
            self::SUPER_ADMIN => '/super-admin',
            self::ADMIN => '/admin',
            self::ICTU_STAFF => '/ictu-staff',
            self::EMPLOYEE => '/employee',
            self::STUDENT => '/student',
        };
    }

    public function role_color(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'red',
            self::ADMIN => 'blue',
            self::ICTU_STAFF => 'green',
            self::EMPLOYEE => 'gray',
            self::STUDENT => 'purple',
        };
    }

    /**
     * Get all roles as array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get roles for dropdown
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $role) {
            $options[$role->value] = $role->label();
        }
        return $options;
    }

    /**
     * Check if role has admin access
     */
    public function isAdmin(): bool
    {
        return in_array($this, [self::ADMIN, self::SUPER_ADMIN]);
    }

    /**
     * Check if role can moderate
     */
    public function canModerate(): bool
    {
        return in_array($this, [self::STAFF, self::TECHNICIAN, self::ADMIN, self::SUPER_ADMIN]);
    }

}