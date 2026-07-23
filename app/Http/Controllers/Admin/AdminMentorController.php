<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminMentorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_mentors');
    }

    public function index()
    {
        $teams = \App\Models\Team::orderBy('name')->get()->keyBy('id');
        $mentors = Mentor::orderBy('name')->paginate(15);
        return view('admin.mentor.index', compact('mentors', 'teams'));
    }

    public function create()
    {
        $teams = \App\Models\Team::orderBy('name')->get();
        return view('admin.mentor.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'is_active' => ['boolean'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $userId = null;
        if (!empty($validated['email'])) {
            // Create linked user account if email provided
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? str()->random(12)),
            ]);
            $user->assignRole('mentor');
            $userId = $user->id;
        }

        Mentor::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'position' => $validated['position'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'team_id' => $validated['team_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'user_id' => $userId,
        ]);

        return redirect()->route('admin.mentor.index')->with('success', 'Mentor berhasil ditambahkan.');
    }

    public function edit(Mentor $mentor)
    {   
        $teams = \App\Models\Team::orderBy('name')->get();
        return view('admin.mentor.edit', compact('mentor', 'teams'));
    }

    public function update(Request $request, Mentor $mentor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . ($mentor->user_id ?? 'NULL')],
            'position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'is_active' => ['boolean'],
            'password' => ['nullable', Password::defaults()],
        ]);

        // Sync linked user
        if (!empty($validated['email'])) {
            if ($mentor->user_id) {
                $mentor->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
                if (!empty($validated['password'])) {
                    $mentor->user->update(['password' => Hash::make($validated['password'])]);
                }
            } else {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? str()->random(12)),
                ]);
                $user->assignRole('mentor');
                $mentor->user_id = $user->id;
            }
        }

        $mentor->name = $validated['name'];
        $mentor->email = $validated['email'] ?? null;
        $mentor->position = $validated['position'] ?? null;
        $mentor->team_id = $validated['team_id'] ?? null;
        $mentor->phone = $validated['phone'] ?? null;
        $mentor->is_active = $request->boolean('is_active', true);
        $mentor->save();

        return redirect()->route('admin.mentor.index')->with('success', 'Mentor berhasil diperbarui.');
    }

    public function destroy(Mentor $mentor)
    {
        $userId = $mentor->user_id;
        $mentor->delete();
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->delete();
            }
        }
        return redirect()->route('admin.mentor.index')->with('success', 'Mentor berhasil dihapus.');
    }
}