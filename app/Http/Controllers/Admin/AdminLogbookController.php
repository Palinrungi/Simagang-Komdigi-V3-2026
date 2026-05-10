<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class AdminLogbookController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_logbook')->only(['index', 'servePhoto']);
        $this->middleware('permission:manage_logbook')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Logbook::with('intern');

        if ($request->filled('q')) {
            $q = '%' . $request->input('q') . '%';
            $query->whereHas('intern', function ($sub) use ($q) {
                $sub->where('name', 'like', $q)->orWhere('institution', 'like', $q);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date('date_to'));
        }

        $logbooks = $query->orderByDesc('date')->paginate(20);

        return view('admin.logbook.index', compact('logbooks'));
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $photoPath = 'private/logbook-photos/' . $filename;

        Logbook::where('photo_path', $photoPath)->firstOrFail();

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

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return back()->with('success', 'Logbook berhasil dihapus.');
    }
}


