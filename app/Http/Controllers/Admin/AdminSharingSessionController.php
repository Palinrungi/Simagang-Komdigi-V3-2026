<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSharingSessionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $today = Carbon::today();

        $totalJadwal = SharingSession::count();
        $hariIni = SharingSession::whereDate('session_date', $today)->count();
        $akanDatang = SharingSession::whereDate('session_date', '>', $today)->count();
        $selesai = SharingSession::whereDate('session_date', '<', $today)->count();

        $sessions = SharingSession::with([
                'speakerUser',
                'moderatorUser',
                'creator',
            ])
            ->when($filter === 'hari-ini', function ($query) use ($today) {
                $query->whereDate('session_date', $today);
            })
            ->when($filter === 'akan-datang', function ($query) use ($today) {
                $query->whereDate('session_date', '>', $today);
            })
            ->when($filter === 'selesai', function ($query) use ($today) {
                $query->whereDate('session_date', '<', $today);
            })
            ->orderBy('session_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sharing-session.index', compact(
            'sessions',
            'filter',
            'totalJadwal',
            'hariIni',
            'akanDatang',
            'selesai'
        ));
    }

    public function create()
    {
        return view('admin.sharing-session.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'speaker' => 'required|string|max:255',
            'moderator' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'evaluation_form_link' => 'nullable|url|max:2048',
            'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['speaker_user_id'] = null;
        $validated['moderator_user_id'] = null;

        if ($request->hasFile('documentation_photo')) {
            $validated['documentation_photo'] = $request
                ->file('documentation_photo')
                ->store('sharing-documentations', 'public');
        }

        SharingSession::create($validated);

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Sharing session berhasil ditambahkan.');
    }

    public function edit(SharingSession $sharingSession)
    {
        return view('admin.sharing-session.edit', compact('sharingSession'));
    }

    public function update(Request $request, SharingSession $sharingSession)
    {
        $validated = $request->validate([
            'speaker' => 'required|string|max:255',
            'moderator' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'evaluation_form_link' => 'nullable|url|max:2048',
            'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['speaker_user_id'] = null;
        $validated['moderator_user_id'] = null;

        if ($request->hasFile('documentation_photo')) {
            if (
                $sharingSession->documentation_photo &&
                Storage::disk('public')->exists($sharingSession->documentation_photo)
            ) {
                Storage::disk('public')->delete($sharingSession->documentation_photo);
            }

            $validated['documentation_photo'] = $request
                ->file('documentation_photo')
                ->store('sharing-documentations', 'public');
        }

        $sharingSession->update($validated);

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Sharing session berhasil diperbarui.');
    }

    public function destroy(SharingSession $sharingSession)
    {
        if (
            $sharingSession->documentation_photo &&
            Storage::disk('public')->exists($sharingSession->documentation_photo)
        ) {
            Storage::disk('public')->delete($sharingSession->documentation_photo);
        }

        $sharingSession->delete();

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Sharing session berhasil dihapus.');
    }
}
