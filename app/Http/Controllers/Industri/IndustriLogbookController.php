<?php

namespace App\Http\Controllers\Industri;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustriLogbookController extends Controller
{
    private function getInternIds(): \Illuminate\Support\Collection
    {
        $industri = Auth::user()->industri;

        if (!$industri) {
            return collect();
        }

        return DB::table('interns')
            ->join('users', 'users.id', '=', 'interns.user_id')
            ->join('pengajuan_details', 'pengajuan_details.email', '=', 'users.email')
            ->join('pengajuans', 'pengajuans.id', '=', 'pengajuan_details.pengajuan_id')
            ->join('lowongans', 'lowongans.id', '=', 'pengajuans.lowongan_id')
            ->where('lowongans.industri_id', $industri->id)
            ->where('interns.is_active', true)
            ->distinct()
            ->pluck('interns.id');
    }

    public function index(Request $request)
    {
        $industri = Auth::user()->industri;

        $internIds = $this->getInternIds();

        $query = Logbook::with('intern')
            ->whereIn('intern_id', $internIds);

        // Filter peserta
        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date('date_to'));
        }

        $logbooks = $query
            ->orderByDesc('date')
            ->paginate(20)
            ->withQueryString();

        $interns = Intern::whereIn('id', $internIds)
            ->join('users', 'interns.user_id', '=', 'users.id')->select('interns.*')->orderBy('users.name')
            ->get();

        return view('industri.logbook.index', compact(
            'industri',
            'logbooks',
            'interns'
        ));
    }

    public function show(Logbook $logbook)
    {
        $internIds = $this->getInternIds();

        abort_unless($internIds->contains($logbook->intern_id), 403);

        $logbook = Logbook::with('intern')
            ->findOrFail($logbook->id);

        return view('industri.logbook.show', compact('logbook'));
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $internIds = $this->getInternIds();

        $photoPath = 'private/logbook-photos/' . $filename;

        Logbook::whereIn('intern_id', $internIds)
            ->where('photo_path', $photoPath)
            ->firstOrFail();

        $fullPath = storage_path('app/' . $photoPath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        return response()->file($fullPath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}