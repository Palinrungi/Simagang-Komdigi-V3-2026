<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SharingSessionExport;
use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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

    /**
     * Export data sharing session ke file Excel berdasarkan filter periode
     */
    public function exportExcel(Request $request)
    {
        $filename = 'Laporan_Sharing_Session_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new SharingSessionExport($request), $filename);
    }

    public function create()
    {
        $activeInternUsers = $this->activeInternUsers();

        return view('admin.sharing-session.create', compact('activeInternUsers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSharingSession($request);
        $participantData = $this->resolveParticipantData($request);

        $validated['evaluation_form_link'] = SharingSession::EVALUATION_FORM_LINK;

        if ($request->hasFile('documentation_photo')) {
            $validated['documentation_photo'] = $request
                ->file('documentation_photo')
                ->store('sharing-documentations', 'public');
        }

        SharingSession::create(array_merge(
            $validated,
            $participantData,
            [
                'created_by' => auth()->id(),
            ]
        ));

        return redirect()
            ->route('admin.sharing-session.index')
            ->with('success', 'Sharing session berhasil ditambahkan.');
    }

    public function edit(SharingSession $sharingSession)
    {
        $activeInternUsers = $this->activeInternUsers();

        return view('admin.sharing-session.edit', compact(
            'sharingSession',
            'activeInternUsers'
        ));
    }

    public function update(Request $request, SharingSession $sharingSession)
    {
        $validated = $this->validateSharingSession($request);
        $participantData = $this->resolveParticipantData($request);

        $validated['evaluation_form_link'] = SharingSession::EVALUATION_FORM_LINK;

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

        $sharingSession->update(array_merge(
            $validated,
            $participantData
        ));

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

    private function activeInternUsers()
    {
        return User::with('intern')
            ->whereHas('intern', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get();
    }

    private function validateSharingSession(Request $request): array
    {
        $validated = $request->validate([
            'speaker_input_type' => 'required|in:manual,intern',
            'speaker_user_id' => 'nullable|required_if:speaker_input_type,intern|exists:users,id',
            'speaker' => 'nullable|required_if:speaker_input_type,manual|string|max:255',

            'moderator_input_type' => 'required|in:manual,intern',
            'moderator_user_id' => 'nullable|required_if:moderator_input_type,intern|exists:users,id',
            'moderator' => 'nullable|required_if:moderator_input_type,manual|string|max:255',

            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'speaker_input_type.required' => 'Pilih sumber nama pemateri terlebih dahulu.',
            'speaker_input_type.in' => 'Pilihan sumber nama pemateri tidak valid.',
            'speaker_user_id.required_if' => 'Pilih pemateri dari daftar intern aktif.',
            'speaker_user_id.exists' => 'Pemateri yang dipilih tidak ditemukan.',
            'speaker.required_if' => 'Masukkan nama pemateri baru.',

            'moderator_input_type.required' => 'Pilih sumber nama moderator terlebih dahulu.',
            'moderator_input_type.in' => 'Pilihan sumber nama moderator tidak valid.',
            'moderator_user_id.required_if' => 'Pilih moderator dari daftar intern aktif.',
            'moderator_user_id.exists' => 'Moderator yang dipilih tidak ditemukan.',
            'moderator.required_if' => 'Masukkan nama moderator baru.',

            'session_date.required' => 'Tanggal sharing session wajib diisi.',
            'session_date.date' => 'Format tanggal tidak valid.',
            'start_time.date_format' => 'Format jam mulai tidak valid.',
            'documentation_photo.image' => 'Dokumentasi harus berupa gambar.',
            'documentation_photo.mimes' => 'Dokumentasi harus berformat JPG, JPEG, PNG, atau WEBP.',
            'documentation_photo.max' => 'Ukuran dokumentasi maksimal 5 MB.',
        ]);

        return collect($validated)->only([
            'session_date',
            'start_time',
            'location',
            'title',
            'description',
        ])->toArray();
    }

    private function resolveParticipantData(Request $request): array
    {
        if ($request->speaker_input_type === 'intern') {
            $speakerUser = $this->findActiveInternUser(
                $request->speaker_user_id,
                'speaker_user_id',
                'Pemateri yang dipilih harus berasal dari user intern yang masih aktif.'
            );

            $speakerUserId = $speakerUser->id;
            $speakerName = $speakerUser->intern?->name ?: $speakerUser->name;
        } else {
            $speakerUserId = null;
            $speakerName = trim((string) $request->speaker);
        }

        if ($request->moderator_input_type === 'intern') {
            $moderatorUser = $this->findActiveInternUser(
                $request->moderator_user_id,
                'moderator_user_id',
                'Moderator yang dipilih harus berasal dari user intern yang masih aktif.'
            );

            $moderatorUserId = $moderatorUser->id;
            $moderatorName = $moderatorUser->intern?->name ?: $moderatorUser->name;
        } else {
            $moderatorUserId = null;
            $moderatorName = trim((string) $request->moderator);
        }

        return [
            'speaker_user_id' => $speakerUserId,
            'speaker' => $speakerName,
            'moderator_user_id' => $moderatorUserId,
            'moderator' => $moderatorName,
        ];
    }

    private function findActiveInternUser($userId, string $field, string $message): User
    {
        $user = User::with('intern')
            ->whereKey($userId)
            ->whereHas('intern', function ($query) {
                $query->where('is_active', true);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                $field => $message,
            ]);
        }

        return $user;
    }
}