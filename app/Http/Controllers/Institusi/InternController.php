<?php

namespace App\Http\Controllers\Institusi;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternController extends Controller
{
    private function getInstitusiInternIds(): \Illuminate\Support\Collection
    {
        $institusi = Auth::user()->institusi;

        if (!$institusi) {
            return collect();
        }

        return DB::table('interns')
            ->join('users', 'users.id', '=', 'interns.user_id')
            ->join('pengajuan_details', 'pengajuan_details.email', '=', 'users.email')
            ->join('pengajuans', 'pengajuans.id', '=', 'pengajuan_details.pengajuan_id')
            ->where('pengajuans.institusi_id', $institusi->id)
            ->where('interns.is_active', true)
            ->pluck('interns.id');
    }

    public function index(Request $request)
    {
        $institusi = Auth::user()->institusi;
        $internIds = $this->getInstitusiInternIds();

        $query = $internIds->isNotEmpty()
            ? Intern::whereIn('id', $internIds)->join('users', 'interns.user_id', '=', 'users.id')->select('interns.*')
            : Intern::whereRaw('1 = 0');

        // Search by name
        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }

        // Filter by status (active / alumni)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'alumni') {
                $query->where('is_active', false);
            }
        }

        $interns = $query
            ->withCount(['attendances', 'logbooks', 'microSkills'])
            ->orderBy('users.name')
            ->paginate(15)
            ->withQueryString();

        return view('institusi.intern.index', compact('institusi', 'interns'));
    }

}