<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industri;
use App\Models\Intern;
use Illuminate\Support\Facades\DB;

class AdminMicroSkillLeaderboardController extends Controller
{
    private function adminInternIds(): \Illuminate\Support\Collection
    {
        $komdigi = Industri::where('nama_industri', 'BBLSDM Komdigi Makassar')->first();

        return Intern::where(function ($q) use ($komdigi) {
            $q->whereNull('pengajuan_detail_id');

            if ($komdigi) {
                $q->orWhereHas('pengajuanDetail.pengajuan.lowongan', function ($lq) use ($komdigi) {
                    $lq->where('industri_id', $komdigi->id);
                });
            }
        })->pluck('id');
    }

    public function index()
    {
        $internIds = $this->adminInternIds();

        $rows = Intern::join('users', 'interns.user_id', '=', 'users.id')
            ->leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->select(
                'interns.id as intern_id',
                'users.name',
                'interns.institution',
                'interns.photo_path',
                DB::raw('COUNT(micro_skill_submissions.id) as total')
            )
            ->whereIn('interns.id', $internIds)
            ->groupBy('interns.id', 'users.name', 'interns.institution', 'interns.photo_path')
            ->orderByDesc('total')
            ->orderBy('users.name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.microskill.leaderboard', compact('rows'));
    }
}