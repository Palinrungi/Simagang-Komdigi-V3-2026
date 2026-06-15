<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminSharingSessionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $today = Carbon::today();

        $sessions = SharingSession::with(['speaker', 'moderator', 'creator'])
            ->when($filter === 'hari-ini', fn($q) => $q->whereDate('session_date', $today))
            ->when($filter === 'akan-datang', fn($q) => $q->whereDate('session_date', '>', $today))
            ->when($filter === 'selesai', fn($q) => $q->whereDate('session_date', '<', $today))
            ->orderBy('session_date', 'desc')
            ->get();

        return view('admin.sharing-session.index', compact('sessions', 'filter'));
    }

    public function create()
    {
        $internUsers = User::where('role', 'intern')
            ->orderBy('name')
            ->get();

        return view('admin.sharing-session.create', compact('internUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'speaker_user_id' => 'required|exists:users,id',
            'moderator_user_id' => 'required|exists:users,id',
            'session_date' => 'required|date',
            'start_time' => 'nullable',
            'location' => 'nullable|string|max:255',
        ]);

        SharingSession::create([
            'created_by' => auth()->id(),
            'speaker_user_id' => $request->speaker_user_id,
            'moderator_user_id' => $request->moderator_user_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Jadwal sharing session berhasil ditambahkan.');
    }

    public function edit(SharingSession $sharingSession)
    {
        $internUsers = User::where('role', 'intern')
            ->orderBy('name')
            ->get();

        return view('admin.sharing-session.edit', compact(
            'sharingSession',
            'internUsers'
        ));
    }

    public function update(Request $request, SharingSession $sharingSession)
    {
        $request->validate([
            'speaker_user_id' => 'required|exists:users,id',
            'moderator_user_id' => 'required|exists:users,id',
            'session_date' => 'required|date',
            'start_time' => 'nullable',
            'location' => 'nullable|string|max:255',
        ]);

        $sharingSession->update([
            'speaker_user_id' => $request->speaker_user_id,
            'moderator_user_id' => $request->moderator_user_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Jadwal sharing session berhasil diperbarui.');
    }

    public function destroy(SharingSession $sharingSession)
    {
        $sharingSession->delete();

        return back()->with('success', 'Jadwal sharing session berhasil dihapus.');
    }
}