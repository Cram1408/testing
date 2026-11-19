<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;

class AdminRoleService
{
    protected static string $permissionsFile = 'admin_permissions.json';
    protected static string $deactivatedFile = 'deactivated_users.json';

    /**
     * Standard permission modules available in the system.
     */
    public static function getModules(): array
    {
        return [
            'dashboard' => [
                'name'        => 'Dashboard Metrics',
                'description' => 'View total sales, revenue, orders, customers, and pending order statistics.',
                'icon'        => 'fa-chart-pie',
            ],
            'products' => [
                'name'        => 'Product & Category Management',
                'description' => 'Add, edit, delete, and manage products, categories, pricing, and images.',
                'icon'        => 'fa-boxes-stacked',
            ],
            'inventory' => [
                'name'        => 'Stock & Inventory Control',
                'description' => 'Monitor live variant stock, adjust quantities, and manage low stock alerts.',
                'icon'        => 'fa-warehouse',
            ],
            'orders' => [
                'name'        => 'Order Management & Fulfillment',
                'description' => 'Process shipments, update delivery statuses, cancellations, returns, and refunds.',
                'icon'        => 'fa-receipt',
            ],
            'customers' => [
                'name'        => 'Customer Management',
                'description' => 'Inspect customer profiles, order histories, contact details, and account status.',
                'icon'        => 'fa-users',
            ],
            'reports' => [
                'name'        => 'Reports & Analytics',
                'description' => 'Generate daily, weekly, and monthly revenue, order volume, and top products reports.',
                'icon'        => 'fa-chart-line',
            ],
            'settings' => [
                'name'        => 'Website Settings',
                'description' => 'Configure store details, delivery charges, announcement bar, payment modes, and coupons.',
                'icon'        => 'fa-sliders',
            ],
            'admins' => [
                'name'        => 'Admin & Role Management',
                'description' => 'Create and deactivate admin staff accounts and configure granular permission matrices.',
                'icon'        => 'fa-user-shield',
            ],
        ];
    }

    /**
     * Predefined roles with their assigned modules.
     */
    public static function getRoleTemplates(): array
    {
        return [
            'super_admin' => [
                'title'       => 'Super Administrator',
                'description' => 'Unrestricted full access across all store systems, settings, and staff credentials.',
                'badge'       => 'bg-amber-600 text-white',
                'modules'     => ['dashboard', 'products', 'inventory', 'orders', 'customers', 'reports', 'settings', 'admins'],
            ],
            'store_manager' => [
                'title'       => 'Store Manager',
                'description' => 'Comprehensive management of products, inventory, orders, customer relations, and reports.',
                'badge'       => 'bg-blue-600 text-white',
                'modules'     => ['dashboard', 'products', 'inventory', 'orders', 'customers', 'reports'],
            ],
            'fulfillment_manager' => [
                'title'       => 'Order Fulfillment Specialist',
                'description' => 'Direct management of order fulfillment, shipping partners, tracking codes, and stock levels.',
                'badge'       => 'bg-purple-600 text-white',
                'modules'     => ['dashboard', 'orders', 'inventory'],
            ],
            'catalog_manager' => [
                'title'       => 'Catalog & Content Editor',
                'description' => 'Create and edit product listings, variants, pricing, descriptions, and category taxonomy.',
                'badge'       => 'bg-emerald-600 text-white',
                'modules'     => ['dashboard', 'products', 'inventory'],
            ],
            'customer_support' => [
                'title'       => 'Customer Support Representative',
                'description' => 'Look up customer profiles, track shipments, check invoices, and review order histories.',
                'badge'       => 'bg-slate-700 text-white',
                'modules'     => ['dashboard', 'orders', 'customers'],
            ],
        ];
    }

    /**
     * Check if an admin has permission for a specific module.
     */
    public static function hasPermission(User $user, string $module): bool
    {
        // Super Admin always has full access to every module
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Must be at least an administrator
        if (!$user->isAdmin()) {
            return false;
        }

        $permissions = self::getPermissionsForUser($user->id);
        return in_array($module, $permissions, true);
    }

    /**
     * Get list of permitted modules for a given user ID.
     */
    public static function getPermissionsForUser(int $userId): array
    {
        $allData = self::readJson(self::$permissionsFile);
        if (isset($allData[$userId]['modules']) && is_array($allData[$userId]['modules'])) {
            return $allData[$userId]['modules'];
        }

        // Default: general dashboard only
        return ['dashboard'];
    }

    /**
     * Get display role title for a user.
     */
    public static function getRoleTitle(User $user): string
    {
        if ($user->isSuperAdmin()) {
            return 'Super Administrator';
        }

        $allData = self::readJson(self::$permissionsFile);
        if (!empty($allData[$user->id]['role_title'])) {
            return $allData[$user->id]['role_title'];
        }

        return 'Store Administrator';
    }

    /**
     * Save/update permissions and role title for an administrator.
     */
    public static function setPermissions(int $userId, array $modules, string $roleTitle = 'Store Administrator'): void
    {
        $allData = self::readJson(self::$permissionsFile);
        $allData[$userId] = [
            'role_title' => $roleTitle,
            'modules'    => array_values(array_unique($modules)),
            'updated_at' => now()->toIso8601String(),
        ];

        self::writeJson(self::$permissionsFile, $allData);
    }

    /**
     * Check if a user account is currently deactivated.
     */
    public static function isDeactivated(int $userId): bool
    {
        $deactivated = self::readJson(self::$deactivatedFile);
        return !empty($deactivated[$userId]);
    }

    /**
     * Deactivate a user account with an optional reason note.
     */
    public static function deactivate(int $userId, string $reason = 'Account suspended by administrator.'): void
    {
        $deactivated = self::readJson(self::$deactivatedFile);
        $deactivated[$userId] = [
            'reason'         => $reason,
            'deactivated_at' => now()->toIso8601String(),
            'deactivated_by' => auth()->id(),
        ];

        self::writeJson(self::$deactivatedFile, $deactivated);
    }

    /**
     * Reactivate a user account.
     */
    public static function activate(int $userId): void
    {
        $deactivated = self::readJson(self::$deactivatedFile);
        if (isset($deactivated[$userId])) {
            unset($deactivated[$userId]);
            self::writeJson(self::$deactivatedFile, $deactivated);
        }
    }

    /**
     * Read JSON file helper from storage.
     */
    protected static function readJson(string $filename): array
    {
        $path = storage_path('app/' . $filename);
        if (!File::exists($path)) {
            return [];
        }

        $content = File::get($path);
        return json_decode($content, true) ?: [];
    }

    /**
     * Write JSON file helper to storage.
     */
    protected static function writeJson(string $filename, array $data): void
    {
        $dir = storage_path('app');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($dir . '/' . $filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

