<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Models\Industri;
use App\Models\Team;

class AdminVerifikasiLowonganController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_verifikasi_lowongan')->only(['index', 'show']);
        $this->middleware('permission:manage_lowongan')->only([
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
                $q->where('nama_industri', '!=', 'BBLSDM Komdigi Makassar');
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
        $lowonganIndustri = Lowongan::with('industri')
            ->whereHas('industri', function ($q) {
                $q->where('nama_industri', '!=', 'BBLSDM Komdigi Makassar');
            })
            ->get();

        $totalLowongan = $lowonganIndustri->count();

        $totalPending = $lowonganIndustri->where('status_verifikasi', 'pending')->count();

        $totalApprove = $lowonganIndustri->where('status_verifikasi', 'disetujui')->count();

        $totalTolak = $lowonganIndustri->where('status_verifikasi', 'ditolak')->count();

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

        return view('admin.verifikasi.index', compact(
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lowongan = Lowongan::with('industri')
        ->findOrFail($id);

        return view('admin.verifikasi.show', compact('lowongan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        $lowongan->update([
            'status_verifikasi' => 'disetujui',
        ]);

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('success', 'Lowongan berhasil disetujui.');
    }

    public function reject(string $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        $lowongan->update([
            'status_verifikasi' => 'ditolak',
        ]);

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('success', 'Lowongan berhasil ditolak.');
    }
}
