<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Mentor;
use App\Models\User;
use App\Models\Institusi;
use App\Models\PengajuanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminInternController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Intern::with(['user', 'mentor', 'team']);

        if ($request->filled('search')) {
            $baseQuery->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('team_id')) {
            $baseQuery->where('team_id', $request->team_id);
        }

        if ($request->filled('mentor_id')) {
            $baseQuery->where('mentor_id', $request->mentor_id);
        }

        $activeInterns = (clone $baseQuery)
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'active_page')
            ->withQueryString();

        $alumniInterns = (clone $baseQuery)
            ->where('is_active', false)
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'alumni_page')
            ->withQueryString();
        $mentors = \App\Models\Mentor::orderByDesc('created_at')->get();
        $teams = \App\Models\Team::orderBy('name')->get();
        
        return view('admin.intern.index', compact('activeInterns', 'alumniInterns', 'mentors', 'teams'));
    }

    public function create()
    {
        
        $calonMagang = \App\Models\PengajuanDetail::with([
                'pengajuan.institusi'
            ])
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'approved');
            })
            ->doesntHave('intern')
            ->get();

        $mentors = \App\Models\Mentor::with('team')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.intern.create', compact('mentors', 'calonMagang'));
    }

    public function store(Request $request)
    {
        // $validTeams = [
        //     'TIM DEA',
        //     'TIM GTA',
        //     'TIM VSGA',
        //     'TIM TA',
        //     'TIM Microskill',
        //     'TIM Media (DiaPus)',
        //     'TIM Tata Usaha (Umum)',
        //     'FGA',
        //     'Keuangan',
        //     'Tim PUSDATIN',
        //     'Tim Perencanaan, Anggaran, Dan Kerja Sama',
        //     'Tim Kepegawaian, Persuratan dan Kearsipan'
        // ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'education_level' => ['required', 'in:SMA/SMK,S1/D4'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'in:Magang,KKN Profesi,PKL,Praktek Industri,Magang Industri,Guru Magang Industri,Job on Training'],
            'mentor_id' => ['required', 'exists:mentors,id'],
            // 'team' => ['nullable', 'string', Rule::in($validTeams)],
            'pengajuan_detail_id' => ['nullable', 'exists:pengajuan_details,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'photo' => ['required', 'image', 'max:2048'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'intern',
        ]);

        // Handle photo upload
        $photo = $request->file('photo');
        if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
            try {
                $extension = $photo->getClientOriginalExtension() ?: ($photo->guessExtension() ?: 'jpg');
                $filename = 'photo_' . time() . '_' . uniqid() . '.' . $extension;
                $destinationPath = storage_path('app/public/photos');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;
                if ($photo->move($destinationPath, $filename) && file_exists($fullPath)) {
                    $photoPath = 'photos/' . $filename;
                } else {
                    return back()->withErrors(['photo' => 'Gagal menyimpan foto.'])->withInput();
                }
            } catch (\Exception $e) {
                return back()->withErrors(['photo' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
            }
        } else {
            return back()->withErrors(['photo' => 'File foto tidak valid.'])->withInput();
        }

        // Use custom institution if provided
        $institution = $validated['institution'];
        if ($institution === '__custom__' && !empty($validated['custom_institution'])) {
            $institution = $validated['custom_institution'];
        }

        $mentor = Mentor::with('team')->find($validated['mentor_id']);

        if (!$mentor) {
            return back()->withErrors(['mentor_id' => 'Mentor tidak ditemukan.']);
        }

        Intern::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'education_level' => $validated['education_level'],
            'major' => $validated['major'],
            'phone' => $validated['phone'],
            'institution' => $institution,
            'purpose' => $validated['purpose'] ?? null,
            'mentor_id' => $mentor->id,
            'team_id' => $mentor->team_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'photo_path' => $photoPath,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
            'pengajuan_detail_id' => $request->pengajuan_detail_id,
        ]);

        return redirect()->route('admin.intern.index')
            ->with('success', 'Data anak magang berhasil ditambahkan.');
    }

    public function show(Intern $intern)
    {
        $intern->load(['attendances' => function ($query) {
            $query->orderBy('date', 'desc')->take(30);
        }, 'logbooks' => function ($query) {
            $query->orderBy('date', 'desc')->take(10);
        }, 'finalReport', 'mentor']);

        $stats = [
            'total_hadir' => $intern->attendances()->where('status', 'hadir')->count(),
            'total_izin' => $intern->attendances()->where('status', 'izin')->count(),
            'total_sakit' => $intern->attendances()->where('status', 'sakit')->count(),
            'total_logbooks' => $intern->logbooks()->count(),
            'has_report' => $intern->finalReport !== null,
        ];

        return view('admin.intern.show', compact('intern', 'stats'));
    }

    public function edit(Intern $intern)
    {
        $mentors = \App\Models\Mentor::with('team')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.intern.edit', compact('intern', 'mentors'));
    }

    public function update(Request $request, Intern $intern)
    {
        $validTeams = [
            'TIM DEA',
            'TIM GTA',
            'TIM VSGA',
            'TIM TA',
            'TIM Microskill',
            'TIM Media (DiaPus)',
            'TIM Tata Usaha (Umum)',
            'FGA',
            'Keuangan',
            'Tim PUSDATIN',
            'Tim Perencanaan, Anggaran, Dan Kerja Sama',
            'Tim Kepegawaian, Persuratan dan Kearsipan'
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $intern->user_id],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'education_level' => ['required', 'in:SMA/SMK,S1/D4'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'in:Magang,KKN Profesi,PKL,Praktek Industri,Magang Industri,Guru Magang Industri,Job on Training'],
            'mentor_id' => ['required', 'exists:mentors,id'],
            // 'team' => ['nullable', 'string', Rule::in($validTeams)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', Password::defaults()],
            'is_active' => ['boolean'],
        ]);

        $intern->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (isset($validated['password'])) {
            $intern->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Use custom institution if provided
        $institution = $validated['institution'];
        if ($institution === '__custom__' && !empty($validated['custom_institution'])) {
            $institution = $validated['custom_institution'];
        }

        $mentor = Mentor::with('team')->find($validated['mentor_id']);

        $data = [
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'education_level' => $validated['education_level'],
            'major' => $validated['major'],
            'phone' => $validated['phone'],
            'institution' => $institution,
            'purpose' => $validated['purpose'] ?? null,
            'mentor_id' => $mentor->id,
            'team_id' => $mentor->team_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    // Delete old photo
                    if ($intern->photo_path) {
                        $oldPath = storage_path('app/public/' . $intern->photo_path);
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    
                    $extension = $photo->getClientOriginalExtension() ?: ($photo->guessExtension() ?: 'jpg');
                    $filename = 'photo_' . time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = storage_path('app/public/photos');
                    
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    
                    $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;
                    if ($photo->move($destinationPath, $filename) && file_exists($fullPath)) {
                        $data['photo_path'] = 'photos/' . $filename;
                    }
                } catch (\Exception $e) {
                    return back()->withErrors(['photo' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
                }
            }
        }

        $intern->update($data);

        return redirect()->route('admin.intern.show', $intern)
            ->with('success', 'Data anak magang berhasil diperbarui.');
    }

    public function destroy(Intern $intern)
    {
        if ($intern->photo_path) {
            Storage::disk('public')->delete($intern->photo_path);
        }

        $userId = $intern->user_id;
        $intern->delete();
        User::find($userId)->delete();

        return redirect()->route('admin.intern.index')
            ->with('success', 'Data anak magang berhasil dihapus.');
    }
}