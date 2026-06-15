<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SharingSessionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $today = Carbon::today();

        $sessions = SharingSession::with(['speaker', 'moderator'])
            ->when($filter === 'hari-ini', function ($query) use ($today) {
                $query->whereDate('session_date', $today);
            })
            ->when($filter === 'akan-datang', function ($query) use ($today) {
                $query->whereDate('session_date', '>', $today);
            })
            ->when($filter === 'selesai', function ($query) use ($today) {
                $query->whereDate('session_date', '<', $today);
            })
            ->orderBy('session_date', 'desc')
            ->get();

        return view('intern.sharing-session.index', compact('sessions', 'filter'));
    }

    public function editMateri(SharingSession $sharingSession)
    {
        if ((int) $sharingSession->speaker_user_id !== (int) auth()->id()) {
            abort(403, 'Anda bukan narasumber pada sharing session ini.');
        }

        $sharingSession->load(['speaker', 'moderator']);

        return view('intern.sharing-session.edit-materi', compact('sharingSession'));
    }

    public function updateMateri(Request $request, SharingSession $sharingSession)
    {
        if ((int) $sharingSession->speaker_user_id !== (int) auth()->id()) {
            abort(403, 'Anda bukan narasumber pada sharing session ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'evaluation_form_link' => 'nullable|url',
        ]);

        $sharingSession->update([
            'title' => $request->title,
            'description' => $request->description,
            'evaluation_form_link' => $request->evaluation_form_link,
        ]);

        return redirect()
            ->route('intern.sharing-session.index')
            ->with('success', 'Materi sharing session berhasil diperbarui.');
    }
}