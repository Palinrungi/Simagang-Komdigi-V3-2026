<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Models\Industri;
use App\Models\Team;

class AdminLowonganController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_lowongan')->only(['index', 'show']);
        $this->middleware('permission:manage_lowongan')->only([
            'create',
            'edit',
            'store',
            'update',
            'destroy',
            'approve',
            'reject',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $industri = auth()->user()->industri;

        $query = Lowongan::with('industri')
            ->whereHas('industri', function ($q) {
                $q->where('nama_industri', 'BBLSDM Komdigi Makassar');
            })
            ->orderByRaw("
                CASE
                    WHEN status_verifikasi = 'pending' THEN 1
                    WHEN status_verifikasi = 'ditolak' THEN 2
                    WHEN status_verifikasi = 'disetujui' THEN 3
                    ELSE 4
                END
            ");

        // search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul_lowongan', 'like', '%' . $search . '%')
                    ->orWhere('posisi_magang', 'like', '%' . $search . '%')
                    ->orWhere('divisi', 'like', '%' . $search . '%')
                    ->orWhere('fasilitas', 'like', '%' . $search . '%')
                    ->orWhereHas('industri', function ($industri) use ($search) {
                        $industri->where('nama_industri', 'like', '%' . $search . '%');
                    });
            });
        }

        // filter status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // filter perusahaan
        if ($request->filled('perusahaan')) {
            $query->whereHas('industri', function ($q) use ($request) {
                $q->where('id', $request->perusahaan);
            });
        }

        // filter divisi
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        // data statistik
        $totalLowongan = Lowongan::whereHas('industri', function ($q) {
            $q->where('nama_industri', 'BBLSDM Komdigi Makassar');
        })->count();

        $totalPending = Lowongan::where('status_verifikasi', 'pending')->count();

        $totalApprove = Lowongan::where('status_verifikasi', 'disetujui')->count();

        $totalTolak = Lowongan::where('status_verifikasi', 'ditolak')->count();

        // data filter dropdown
        $perusahaans = Industri::orderBy('nama_industri')->get();

        $divisis = Lowongan::select('divisi')
            ->whereNotNull('divisi')
            ->distinct()
            ->pluck('divisi');

        // data lowongan
        $lowongans = $query
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        return view('admin.lowongan.index', compact(
            'lowongans',
            'totalLowongan',
            'totalPending',
            'totalApprove',
            'totalTolak',
            'perusahaans',
            'divisis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.lowongan.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $industri = Industri::where('nama_industri', 'BBLSDM Komdigi Makassar')
            ->firstOrFail();

        $statusverifikasi = 'disetujui';

        // Validasi
        $request->validate([
            'judul_lowongan'      => 'required|string|max:255',
            'posisi_magang'       => 'required|string|max:255',
            'team_id'             => 'required|exists:teams,id',
            'deskripsi_pekerjaan' => 'required|string',
            'requirements'        => 'required|string',
            'fasilitas'           => 'required|string',
            'kuota_peserta'       => 'required|integer|min:1',
            'status'              => 'required|in:aktif,nonaktif',
        ], [
            'required' => ':attribute wajib diisi.',
        ]);

        // Simpan lowongan
        $team = Team::find($request->team_id);

        Lowongan::create([
            'industri_id'         => $industri->id,
            'judul_lowongan'      => $request->judul_lowongan,
            'posisi_magang'       => $request->posisi_magang,
            'team_id'             => $request->team_id,
            'divisi'              => $team?->name,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            'requirements'        => $request->requirements,
            'fasilitas'           => $request->fasilitas,
            'kuota_peserta'       => $request->kuota_peserta,

            // Mapping status
            'status' => $request->status === 'aktif'
                ? 'dibuka'
                : 'ditutup',
            'status_verifikasi'   => $statusverifikasi,
        ]);

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan magang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lowongan = Lowongan::with('industri')
        ->findOrFail($id);

        return view('admin.lowongan.show', compact('lowongan'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);
        // if (!$this->canEditLowongan($lowongan)) {
        //     abort(403, 'Anda tidak memiliki akses untuk mengedit lowongan ini.');
        // }
        $teams = Team::orderBy('name')->get();
        return view('admin.lowongan.edit', compact('lowongan', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lowongan = Lowongan::findOrFail($id);
        // if (!$this->canEditLowongan($lowongan)) {
        //     abort(403, 'Anda tidak memiliki akses untuk mengedit lowongan ini.');
        // }
        $request->validate([
            'judul_lowongan'      => 'required|string|max:255',
            'posisi_magang'       => 'required|string|max:255',
            'divisi'              => 'required|string|max:255|exists:teams,name',
            'deskripsi_pekerjaan' => 'required|string',
            'requirements'        => 'required|string',
            'fasilitas'           => 'required|string',
            'kuota_peserta'       => 'required|integer|min:1',
            'status'              => 'required|in:aktif,nonaktif',
        ], [
            'required' => ':attribute wajib diisi.',
        ]);
        $lowongan->update([
            'judul_lowongan'      => $request->judul_lowongan,
            'posisi_magang'       => $request->posisi_magang,
            'divisi'              => $request->divisi,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            'requirements'        => $request->requirements,
            'fasilitas'           => $request->fasilitas,
            'kuota_peserta'       => $request->kuota_peserta,
            'status'              => $request->status === 'aktif' ? 'dibuka' : 'ditutup',
        ]);
        return redirect()
            ->route('admin.lowongan.show', $lowongan->id)
            ->with('success', 'Lowongan magang berhasil diperbarui.');
    }


     /**

     * Determine whether the current admin may edit the lowongan.

     */

    private function canEditLowongan(Lowongan $lowongan): bool
    {
        $user = auth()->user();
        $industri = $user->industri;

        if ($industri) {
            return $lowongan->industri_id === $industri->id;
        }
        return $lowongan->industri_id === null;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        $lowongan->delete();

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    public function approve(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        $lowongan->update([
            'status_verifikasi' => 'disetujui',
        ]);

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil disetujui.');
    }

    public function reject(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        $lowongan->update([
            'status_verifikasi' => 'ditolak',
        ]);

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil ditolak.');
    }
}
