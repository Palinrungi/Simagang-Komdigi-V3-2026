<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class LogbookController extends Controller
{
    private function makeOneTimeLogbookPhotoUrl(?string $photoPath): ?string
    {
        if (!$photoPath) {
            return null;
        }

        $filename = basename($photoPath);
        $token = Str::random(64);

        Cache::put(
            "intern-logbook-token:{$token}",
            [
                'user_id' => Auth::id(),
                'filename' => $filename,
            ],
            now()->addMinutes(5)
        );

        return route('intern.logbook.photo', [
            'filename' => $filename,
            'token' => $token,
        ]);
    }

    public function index()
    {
        $intern = Auth::user()->intern;
        $logbooks = Logbook::where('intern_id', $intern->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Aggregate counts across all logbook records for this intern
        $totalLogbooks = Logbook::where('intern_id', $intern->id)->count();
        $thisMonthCount = Logbook::where('intern_id', $intern->id)
            ->where('date', '>=', now()->startOfMonth())
            ->count();
        
        // Calculate days not yet filled (belum mengerjakan = total hadir - total logbook)
        $totalHadir = Attendance::where('intern_id', $intern->id)->count();
        $belumDikerjakan = max(0, $totalHadir - $totalLogbooks);

        $cekaktif = $intern && $intern->is_active;

        $logbooks->each(function ($logbook) {
            $logbook->photo_url = $this->makeOneTimeLogbookPhotoUrl($logbook->photo_path);
        });

        return view('intern.logbook.index', compact('logbooks', 'totalLogbooks', 'thisMonthCount', 'belumDikerjakan', 'cekaktif'));
    }

    public function create()
    {
        return view('intern.logbook.create');
    }

    public function store(Request $request)
    {
        $intern = Auth::user()->intern;

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:1000'],
            // 'photo' => ['nullable', 'image', 'max:2048'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048'
            ],
        ]);

        $data = [
            'intern_id' => $intern->id,
            'date' => $validated['date'],
            'activity' => $validated['activity'],
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
            ];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return back()->withErrors([
                    'photo' => 'Tipe file tidak valid.'
                ])->withInput();
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    // $extension = $photo->guessExtension() ?: 'jpg';

                    $filename = Str::uuid() . '.jpg';

                    $destinationPath = storage_path('app/private/logbook-photos');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($photo)
                        ->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/logbook-photos/' . $filename,
                        (string) $image
                    );

                    $data['photo_path'] = 'private/logbook-photos/' . $filename;
                } catch (\Exception $e) {
                    return back()->withErrors([
                        'photo' => 'Gagal upload foto.'
                    ])->withInput();
                }
            }
        }

        $logbook = Logbook::create($data);
        

        return redirect()->route('intern.logbook.index')
            ->with('success', 'Logbook berhasil disimpan.');
    }

    public function edit(Logbook $logbook)
    {
        $this->authorize('update', $logbook);

        $logbook->photo_url = $this->makeOneTimeLogbookPhotoUrl($logbook->photo_path);

        return view('intern.logbook.edit', compact('logbook'));
    }

    public function show(Logbook $logbook)
    {
        $this->authorize('view', $logbook);

        $logbook->photo_url = $this->makeOneTimeLogbookPhotoUrl($logbook->photo_path);

        return view('intern.logbook.show', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $this->authorize('update', $logbook);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048'
            ],
        ]);

        $data = [
            'date' => $validated['date'],
            'activity' => $validated['activity'],
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
            ];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return back()->withErrors([
                    'photo' => 'Tipe file tidak valid.'
                ])->withInput();
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    // Delete old photo
                    if ($logbook->photo_path) {
                        $oldPath = storage_path('app/private/' . $logbook->photo_path);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    
                    // $extension = $photo->guessExtension() ?: 'jpg';

                    $filename = Str::uuid() . '.jpg';

                    $destinationPath = storage_path('app/private/logbook-photos');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($photo)
                        ->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/logbook-photos/' . $filename,
                        (string) $image
                    );

                    $data['photo_path'] = 'private/logbook-photos/' . $filename;
                } catch (\Exception $e) {
                    return back()->withErrors([
                        'photo' => 'Gagal upload foto.'
                    ])->withInput();
                }
            }
        }

        $logbook->update($data);

        return redirect()->route('intern.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        $this->authorize('delete', $logbook);

        if ($logbook->photo_path) {
            $photoPath = storage_path('app/private/' . $logbook->photo_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $logbook->delete();

        return redirect()->route('intern.logbook.index')
            ->with('success', 'Logbook berhasil dihapus.');
    }
    public function exportExcel()
    {
        $intern = Auth::user()->intern;
        if (!$intern) {
            return redirect()->back()->with('error', 'Data peserta magang tidak ditemukan.');
        }

        // UBAH DARI 'desc' MENJADI 'asc' AGAR TANGGAL LAMA DI ATAS, BARU KE TANGGAL TERBARU DI BAWAHNYA
        $logbooks = Logbook::where('intern_id', $intern->id)
            ->orderBy('date', 'asc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Laporan di Baris 1
        $sheet->setCellValue('A1', 'REKAPITULASI LOGBOOK HARIAN PESERTA MAGANG');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Header Kolom Tabel di Baris 3
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Tanggal');
        $sheet->setCellValue('C3', 'Jam Masuk (Absensi)');
        $sheet->setCellValue('D3', 'Jam Keluar');
        $sheet->setCellValue('E3', 'Aktivitas / Kegiatan');

        // Styling Header Tabel (Baris 3)
        $sheet->getStyle('A3:E3')->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A8A'], // Biru Tua
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(25);

        $row = 4;
        $no = 1;
        foreach ($logbooks as $log) {
            $attendance = Attendance::where('intern_id', $intern->id)
                ->whereDate('date', $log->date)
                ->first();

            $jamMasuk = $attendance && $attendance->check_in 
                ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') 
                : '-';
            
            $jamKeluar = '16:00';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, \Carbon\Carbon::parse($log->date)->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $jamMasuk);
            $sheet->setCellValue('D' . $row, $jamKeluar);
            $sheet->setCellValue('E' . $row, strip_tags($log->activity));

            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        $lastRow = $row - 1;

        if ($lastRow >= 3) {
            $sheet->getStyle('A3:E' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $sheet->getStyle('A4:D' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E4:E' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A3:E' . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Logbook-' . Str::slug(Auth::user()->name) . '-' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    /**
     * Serve private logbook photo with one-time token validation
     */
    public function servePhoto(Request $request, $filename)
    {
        $intern = Auth::user()->intern;
        $filePath = storage_path('app/private/logbook-photos/' . $filename);

        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $token = $request->query('token');
        if ($token) {
            $cacheKey = "intern-logbook-token:{$token}";
            $tokenData = Cache::get($cacheKey);

            if (
                !$tokenData ||
                ($tokenData['user_id'] ?? null) !== Auth::id() ||
                ($tokenData['filename'] ?? null) !== $filename
            ) {
                abort(404, 'File not found');
            }

            // Token is valid, don't consume it - keep it in cache for the TTL duration
        } else {
            // No token provided, fall back to ownership check
            // Policy check below will enforce ownership and role-based access.
        }

        $logbook = Logbook::where('photo_path', 'private/logbook-photos/' . $filename)
            ->first();

        if (!$logbook) {
            abort(403, 'Unauthorized');
        }

        $this->authorize('view', $logbook);

        // Validate the file path to prevent directory traversal
        if (!str_starts_with(realpath($filePath) ?: '', realpath(storage_path('app/private/logbook-photos')) ?: '')) {
            abort(403, 'Unauthorized');
        }

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}