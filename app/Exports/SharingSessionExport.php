<?php

namespace App\Exports;

use App\Models\SharingSession;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SharingSessionExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filterType;
    protected $startDate;
    protected $endDate;
    protected $month;
    protected $year;
    protected $rowNumber = 0;

    public function __construct($request)
    {
        $this->filterType = $request->get('filter_type', 'all');
        $this->startDate  = $request->get('start_date');
        $this->endDate    = $request->get('end_date');
        $this->month      = $request->get('month');
        $this->year       = $request->get('year', date('Y'));
    }

    public function query()
    {
        $query = SharingSession::query();

        if ($this->filterType === 'weekly' && $this->startDate && $this->endDate) {
            $query->whereBetween('session_date', [$this->startDate, $this->endDate]);
        } elseif ($this->filterType === 'monthly' && $this->month) {
            $query->whereYear('session_date', $this->year)
                  ->whereMonth('session_date', $this->month);
        } elseif ($this->filterType === 'yearly' && $this->year) {
            $query->whereYear('session_date', $this->year);
        }

        return $query->orderBy('session_date', 'asc');
    }

    // 1. HEADERS TANPA DOKUMENTASI (HANYA 7 KOLOM)
    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL & WAKTU',
            'JUDUL SHARING SESSION',
            'NARASUMBER / PEMATERI',
            'MODERATOR',
            'LOKASI / RUANGAN',
            'STATUS JADWAL',
        ];
    }

    // 2. PEMETAAN DATA
    public function map($row): array
    {
        $this->rowNumber++;

        // Status Jadwal
        $statusJadwal = 'Akan Datang';
        if ($row->session_date) {
            if ($row->session_date->isToday()) {
                $statusJadwal = 'Hari Ini';
            } elseif ($row->session_date->lt(now()->startOfDay())) {
                $statusJadwal = 'Selesai';
            }
        }

        return [
            $this->rowNumber,
            $row->session_date ? $row->session_date->translatedFormat('l, d F Y') . ($row->start_time ? ' - ' . \Carbon\Carbon::parse($row->start_time)->format('H:i') . ' WITA' : '') : '-',
            $row->title ?? 'Materi Belum Diisi',
            $row->speaker ?? '-',
            $row->moderator ?? '-',
            $row->location ?? '-',
            $statusJadwal,
        ];
    }

    // 3. STYLING ALL BORDERS DAN WARNA HEADER
    public function styles(Worksheet $sheet)
    {
        $totalRows = $this->rowNumber + 1; // Total baris (Header + Data)
        $highestColumn = 'G'; // Kolom terakhir adalah G (Status Jadwal)

        $range = "A1:{$highestColumn}{$totalRows}";

        // Terapkan Border Ke Seluruh Sel Tabel (All Borders)
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '9CA3AF'], // Garis abu-abu rapi
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style Khusus Baris Header (Baris 1)
        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '1E3A8A'], // Warna Biru Gelap SIMAGANG
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center Alignment untuk Kolom NO (A) dan STATUS JADWAL (G)
        $sheet->getStyle("A2:A{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G2:G{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}