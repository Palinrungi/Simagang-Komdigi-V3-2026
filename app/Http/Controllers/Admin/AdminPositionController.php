<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminPositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('team')->latest()->get();
        $teams = Team::all();
        return view('admin.positions.index', compact('positions', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        Position::create([
            'name' => $request->name,
            'team_id' => $request->team_id,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Posisi magang berhasil ditambahkan!');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->back()->with('success', 'Posisi magang berhasil dihapus!');
    }
}