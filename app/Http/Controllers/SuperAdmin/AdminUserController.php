<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    protected const STAFF_ROLES = ['admin', 'super_admin'];

    public function index()
    {
        $admins = User::whereIn('role', self::STAFF_ROLES)
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(self::STAFF_ROLES)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    public function edit(User $admin)
    {
        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(self::STAFF_ROLES)],
            'status' => ['required', Rule::in(['active', 'blocked'])],
        ]);

        if ($admin->id === auth()->id()) {
            $validated['role'] = 'super_admin';
            $validated['status'] = 'active';
        }

        $this->guardLastSuperAdmin($admin, $validated);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $admin->password,
        ]);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function toggleStatus(User $admin)
    {
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $this->guardLastSuperAdmin($admin, ['status' => $admin->status === 'active' ? 'blocked' : 'active']);

        $admin->update([
            'status' => $admin->status === 'active' ? 'blocked' : 'active',
        ]);

        return back()->with('success', 'Admin status updated.');
    }

    public function destroy(User $admin)
    {
        if ($admin->id === auth()->id()) {
            return redirect()->route('superadmin.admins.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($admin->role === 'super_admin' && $this->countSuperAdmins() <= 1) {
            return redirect()->route('superadmin.admins.index')
                ->with('error', 'Cannot delete the last super admin account.');
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }

    protected function guardLastSuperAdmin(User $admin, array $validated): void
    {
        if ($admin->role === 'super_admin'
            && $this->countSuperAdmins() <= 1
            && (($validated['role'] ?? null) !== 'super_admin' || ($validated['status'] ?? 'active') !== 'active')) {
            abort(422, 'Cannot demote or block the last super admin account.');
        }
    }

    protected function countSuperAdmins(): int
    {
        return User::where('role', 'super_admin')->count();
    }
}
