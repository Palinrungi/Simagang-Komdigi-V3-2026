<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SanitizeAttendancePhotosCommand extends Command
{
    protected $signature = 'attendance:sanitize-photos 
                            {--months=3 : Hapus foto absensi yang lebih lama dari jumlah bulan ini}
                            {--dry-run : Simulasi tanpa menghapus file dan tanpa mengubah database}
                            {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Sanitasi file foto absensi lama tanpa menghapus data absensi.';

    public function handle(): int
    {
        $months = (int) $this->option('months');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if ($months < 1) {
            $this->error('Opsi --months minimal bernilai 1.');
            return self::FAILURE;
        }

        if (! Schema::hasTable('attendances')) {
            $this->error('Tabel attendances tidak ditemukan.');
            return self::FAILURE;
        }

        $photoColumns = collect(['photo_path', 'photo_checkout'])
            ->filter(fn ($column) => Schema::hasColumn('attendances', $column))
            ->values();

        if ($photoColumns->isEmpty()) {
            $this->error('Kolom foto absensi tidak ditemukan. Minimal harus ada photo_path atau photo_checkout.');
            return self::FAILURE;
        }

        $dateColumn = collect(['date', 'attendance_date', 'tanggal', 'created_at'])
            ->first(fn ($column) => Schema::hasColumn('attendances', $column));

        if (! $dateColumn) {
            $this->error('Kolom tanggal tidak ditemukan. Tambahkan date, attendance_date, tanggal, atau created_at.');
            return self::FAILURE;
        }

        $cutoffDate = now()->subMonths($months);

        $query = DB::table('attendances')
            ->where($dateColumn, '<=', $cutoffDate)
            ->where(function ($query) use ($photoColumns) {
                foreach ($photoColumns as $column) {
                    $query->orWhereNotNull($column);
                }
            });

        $total = $query->count();

        $this->info("Mode       : " . ($dryRun ? 'DRY RUN / simulasi' : 'EKSEKUSI'));
        $this->info("Batas waktu: data absensi sebelum atau sama dengan {$cutoffDate->format('Y-m-d H:i:s')}");
        $this->info("Total data : {$total}");

        if ($total === 0) {
            $this->info('Tidak ada foto absensi lama yang perlu disanitasi.');
            return self::SUCCESS;
        }

        if (! $dryRun && ! $force) {
            if (! $this->confirm('Foto lama akan dihapus dan kolom foto pada database akan dikosongkan. Lanjutkan?')) {
                $this->warn('Proses dibatalkan.');
                return self::SUCCESS;
            }
        }

        $deletedFiles = 0;
        $clearedRows = 0;

        $query->orderBy('id')->chunkById(100, function ($attendances) use ($photoColumns, $dryRun, &$deletedFiles, &$clearedRows) {
            foreach ($attendances as $attendance) {
                $updateData = [];

                foreach ($photoColumns as $column) {
                    $path = $attendance->{$column} ?? null;

                    if (! $path) {
                        continue;
                    }

                    if ($dryRun) {
                        $this->line("[DRY RUN] {$column}: {$path}");
                        continue;
                    }

                    $this->deletePhotoFile($path);
                    $deletedFiles++;

                    $updateData[$column] = null;
                }

                if (! $dryRun && ! empty($updateData)) {
                    DB::table('attendances')
                        ->where('id', $attendance->id)
                        ->update($updateData);

                    $clearedRows++;
                }
            }
        });

        if ($dryRun) {
            $this->info('Simulasi selesai. Belum ada file atau database yang diubah.');
        } else {
            $this->info("Sanitasi selesai. File diproses: {$deletedFiles}. Baris database dibersihkan: {$clearedRows}.");
        }

        return self::SUCCESS;
    }

    private function deletePhotoFile(string $path): void
    {
        $cleanPath = ltrim($path, '/');

        $candidatePaths = collect([
            $cleanPath,
            'private/' . $cleanPath,
            'private/attendance-photos/' . basename($cleanPath),
            'attendance-photos/' . basename($cleanPath),
        ])->unique();

        foreach ($candidatePaths as $candidatePath) {
            if (Storage::disk('local')->exists($candidatePath)) {
                Storage::disk('local')->delete($candidatePath);
                return;
            }
        }
    }
}