<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminRoleService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Show Role & Permission Matrix.
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        $modules = AdminRoleService::getModules();
        $roleTemplates = AdminRoleService::getRoleTemplates();

        return view('admin.roles.index', compact('admins', 'modules', 'roleTemplates'));
    }

    /**
     * Batch save permissions for all administrators.
     */
    public function update(Request $request)
    {
        $permissionsData = $request->input('permissions', []);
        $roleTitles = $request->input('role_titles', []);

        foreach ($permissionsData as $userId => $modules) {
            $user = User::find($userId);
            if ($user && !$user->isSuperAdmin()) {
                $roleTitle = $roleTitles[$userId] ?? AdminRoleService::getRoleTitle($user);
                AdminRoleService::setPermissions((int) $userId, (array) $modules, $roleTitle);
            }
        }

        return back()->with('success', 'Role and module permissions matrix updated successfully.');
    }
}

