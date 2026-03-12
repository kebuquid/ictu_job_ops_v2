<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter (alias: 'role') – enforces both authentication and role-based access.
 *
 * Usage in Routes.php:  ['filter' => 'role:1']
 *
 * Role IDs (from the `roles` table):
 *   1 → Super Admin  (/super-admin)
 *   2 → Admin        (/admin)
 *   3 → ICTU Staff   (/ictu-staff)
 *   4 → Employee     (/employee)
 *   5 → Student      (/student)
 *
 * Behaviour:
 *   - Not authenticated  → save intended URL in session, redirect to /login
 *   - Wrong role         → redirect to the user's own dashboard with an error flash
 */
class AuthFilter implements FilterInterface
{
    /**
     * Maps role_id → dashboard path (mirrors the `roles.url_path` column).
     */
    private const ROLE_PATHS = [
        1 => '/super-admin/dashboard',
        2 => '/admin/dashboard',
        3 => '/ictu-staff/dashboard',
        4 => '/employee/dashboard',
        5 => '/student/dashboard',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session()->get('user');

        // ── 1. Unauthenticated ──────────────────────────────────────────────
        if (! $user) {
            session()->set('login_redirect', current_url());
            return redirect()->to(base_url('login'));
        }

        // ── 2. Role check (only when the route group passes a role argument) ─
        if (! empty($arguments)) {
            $allowedRoleIds = array_map('intval', $arguments); // e.g. [1] or [3]
            $userRoleId     = (int) ($user['role_id'] ?? 0);

            if (! in_array($userRoleId, $allowedRoleIds, true)) {
                // Send them to their own dashboard instead of exposing a 403.
                $ownPath = self::ROLE_PATHS[$userRoleId] ?? '/login';
                return redirect()->to(base_url(ltrim($ownPath, '/')))->with('error', 'You do not have access to that area.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the request.
    }
}
