<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    public function index(Request $request)
    {
        $query = User::query()
            ->with('roles')
            ->where(function ($builder) {
                $builder->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', [
                        'super_admin',
                        'admin_full',
                        'admin_user_manager',
                        'admin_data_manager',
                    ]);
                })->orWhereIn('role', [
                    'super_admin',
                    'admin_full',
                    'admin_user_manager',
                    'admin_data_manager',
                    'admin',
                ]);
            });

        if ($request->filled('search')) {
            $search = '%' . trim((string) $request->input('search')) . '%';
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search);
            });
        }

        $accounts = $query->orderByRaw("CASE role WHEN 'super_admin' THEN 0 WHEN 'admin_full' THEN 1 WHEN 'admin_user_manager' THEN 2 WHEN 'admin_data_manager' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->paginate(15);

        $accounts->appends($request->query());

        return view('admin.account.index', compact('accounts'));
    }

    public function create()
    {
        $roleOptions = [
            'admin_full' => 'Admin Full Access',
            'admin_user_manager' => 'Admin User Manager',
            'admin_data_manager' => 'Admin Data Manager',
        ];

        return view('admin.account.create', compact('roleOptions'));
    }

    public function store(Request $request)
    {
        $roleOptions = ['admin_full', 'admin_user_manager', 'admin_data_manager'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
            'role' => ['required', Rule::in($roleOptions)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_unless($user->isAdmin(), 404);

        $roleOptions = [
            'admin_full' => 'Admin Full Access',
            'admin_user_manager' => 'Admin User Manager',
            'admin_data_manager' => 'Admin Data Manager',
        ];

        return view('admin.account.edit', compact('user', 'roleOptions'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        $roleOptions = ['admin_full', 'admin_user_manager', 'admin_data_manager'];
        $allowedRoles = $user->isSuperAdmin() ? ['super_admin'] : $roleOptions;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
        ]);

        $targetRole = $user->isSuperAdmin() ? 'super_admin' : $validated['role'];

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles([$targetRole]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->isSuperAdmin()) {
            return back()->withErrors([
                'error' => 'Super Admin tidak bisa dihapus.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }
}