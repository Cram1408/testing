<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminRoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * List all administrators and staff accounts.
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->latest()
            ->get();

        $modules = AdminRoleService::getModules();
        $roleTemplates = AdminRoleService::getRoleTemplates();

        return view('admin.admins.index', compact('admins', 'modules', 'roleTemplates'));
    }

    /**
     * Show form to create a new administrator.
     */
    public function create()
    {
        $modules = AdminRoleService::getModules();
        $roleTemplates = AdminRoleService::getRoleTemplates();

        return view('admin.admins.create', compact('modules', 'roleTemplates'));
    }

    /**
     * Store new administrator account and assign role & permissions.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:100|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'role_type'  => 'required|in:super_admin,admin',
            'role_title' => 'required|string|max:100',
            'modules'    => 'nullable|array',
            'modules.*'  => 'string',
            'phone'      => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => strtolower($validated['email']),
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role_type'],
            'phone'             => $validated['phone'] ?? null,
            'email_verified_at' => now(),
        ]);

        $modulesToAssign = $validated['role_type'] === 'super_admin'
            ? array_keys(AdminRoleService::getModules())
            : ($validated['modules'] ?? ['dashboard']);

        AdminRoleService::setPermissions($user->id, $modulesToAssign, $validated['role_title']);

        return redirect()->route('admin.admins.index')->with('success', "Administrator {$user->name} created successfully.");
    }

    /**
     * Show edit form for an existing administrator.
     */
    public function edit(User $admin)
    {
        // Protect regular admin from editing other super admins unless they are a super admin
        if ($admin->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $modules = AdminRoleService::getModules();
        $roleTemplates = AdminRoleService::getRoleTemplates();
        $userModules = AdminRoleService::getPermissionsForUser($admin->id);
        $roleTitle = AdminRoleService::getRoleTitle($admin);

        return view('admin.admins.edit', compact('admin', 'modules', 'roleTemplates', 'userModules', 'roleTitle'));
    }

    /**
     * Update administrator details, role, and module permissions.
     */
    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:100|unique:users,email,' . $admin->id,
            'password'   => 'nullable|string|min:6|confirmed',
            'role_type'  => 'required|in:super_admin,admin',
            'role_title' => 'required|string|max:100',
            'modules'    => 'nullable|array',
            'modules.*'  => 'string',
            'phone'      => 'nullable|string|max:20',
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => strtolower($validated['email']),
            'role'  => $validated['role_type'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        $modulesToAssign = $validated['role_type'] === 'super_admin'
            ? array_keys(AdminRoleService::getModules())
            : ($validated['modules'] ?? ['dashboard']);

        AdminRoleService::setPermissions($admin->id, $modulesToAssign, $validated['role_title']);

        return redirect()->route('admin.admins.index')->with('success', "Administrator {$admin->name} updated successfully.");
    }

    /**
     * Deactivate or reactivate an administrator account.
     */
    public function toggleStatus(User $admin)
    {
        if ($admin->email === 'adminram14@achaarg.com' || $admin->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate the primary Super Administrator or your own account.');
        }

        if ($admin->is_deactivated) {
            AdminRoleService::activate($admin->id);
            $msg = "Administrator {$admin->name} has been reactivated.";
        } else {
            AdminRoleService::deactivate($admin->id, 'Deactivated by Super Admin ' . auth()->user()->name);
            $msg = "Administrator {$admin->name} has been deactivated.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete an administrator account.
     */
    public function destroy(User $admin)
    {
        if ($admin->email === 'adminram14@achaarg.com' || $admin->id === auth()->id()) {
            return back()->with('error', 'You cannot delete the primary Super Administrator or your own active account.');
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', "Administrator {$name} deleted successfully.");
    }
}

