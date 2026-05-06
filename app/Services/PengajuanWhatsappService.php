<?php

namespace App\Services;

use App\Models\Pengajuan;

class PengajuanWhatsappService
{
    public function payloadFor(Pengajuan $pengajuan): array
    {
        $pengajuan->loadMissing('institusi');

        $phone = $this->normalizePhone($pengajuan->institusi?->no_hp);

        if (! in_array($pengajuan->status, ['approved', 'rejected', 'revised'], true)) {
            return [
                'phone' => $phone,
                'message' => null,
                'url' => null,
            ];
        }

        $message = $this->buildMessage($pengajuan);

        return [
            'phone' => $phone,
            'message' => $message,
            'url' => $phone ? 'https://wa.me/' . $phone . '?text=' . rawurlencode($message) : null,
        ];
    }

    public function buildMessage(Pengajuan $pengajuan): string
    {
        $pengajuan->loadMissing(['institusi', 'details']);

        $institusi = $pengajuan->institusi;
        $institusiName = $institusi?->nama_institusi ?? 'Institusi';
        $greeting = $this->buildGreeting($institusi?->jenis_institusi, $institusiName, $institusi?->fakultas, $institusi?->departemen);
        $namaCalonMagang = $this->buildApplicantSummary($pengajuan);
        $statusLabel = $this->statusLabel($pengajuan->status);
        $periode = trim(($pengajuan->start_date ?? '-') . ' s/d ' . ($pengajuan->end_date ?? '-'));
        $nomorSurat = $pengajuan->nomor_surat_balasan ?: ($pengajuan->no_surat ?? '-');
        $detailUrl = route('institusi.pengajuan.show', ['id' => $pengajuan->id]);
        $opening = 'Assalamualaikum warahmatullahi wabarakatuh';

        $lines = [
            $opening . ',',
            '',
            '*' . $greeting . '*',
            '',
            'Terima kasih atas pengajuan magang yang telah disampaikan kepada BBPSDM Komdigi Makassar melalui Website Resmi Simagang. Bersama ini kami sampaikan informasi status pengajuan Anda:',
            '- Nomor Surat: ' . $nomorSurat,
            '- Nama Calon Magang: ' . $namaCalonMagang,
            '- Periode Magang: ' . $periode,
            '- Status: ' . $statusLabel,
        ];

        if ($pengajuan->status === 'approved') {
            $lines[] = '';
            $lines[] = 'Kami telah menerima dan membaca surat pengajuan yang Anda kirimkan, dengan ini kami menyampaikan bahwa pengajuan Anda telah kami setujui. Silakan mengakses Website Simagang untuk mengunduh surat balasan dari kami:';
            $lines[] = $detailUrl;
        } elseif ($pengajuan->status === 'rejected') {
            $lines[] = '';
            $lines[] = 'Kami telah menerima dan membaca surat pengajuan yang Anda kirimkan, namun tidak bisa melanjutkan proses ini karena kebutuhan penerimaan program Magang saat ini sudah terpenuhi sesuai dengan periode yang dipilih.';
        } elseif ($pengajuan->status === 'revised') {
            $lines[] = '';
            $lines[] = 'Pengajuan Anda memerlukan revisi.';

            if ($pengajuan->admin_note) {
                $lines[] = 'Catatan revisi dari kami:';
                $lines[] = $pengajuan->admin_note;
            }

            $lines[] = '';
            $lines[] = 'Silakan melakukan perbaikan sesuai catatan melalui Website Simagang dan mengajukan kembali untuk proses selanjutnya.';
        }

        $lines[] = '';
        $lines[] = 'Atas perhatian dan kerja samanya, kami ucapkan terima kasih.';

        return implode("\n", $lines);
    }

    public function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phone) ?? '';

        if ($normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, '62')) {
            return $normalized;
        }

        if (str_starts_with($normalized, '0')) {
            return '62' . substr($normalized, 1);
        }

        if (str_starts_with($normalized, '8')) {
            return '62' . $normalized;
        }

        return $normalized;
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Perlu Revisi',
            default => ucfirst($status),
        };
    }

    private function buildGreeting(?string $jenisInstitusi, string $namaInstitusi, ?string $fakultas, ?string $departemen): string
    {
        $jenis = strtolower(trim((string) $jenisInstitusi));

        if ($jenis === 'sekolah') {
            return 'Yth. Tata Usaha ' . $namaInstitusi;
        }

        $parts = array_filter([
            'Yth. Bapak/Ibu',
            $namaInstitusi,
            $fakultas ? 'Fakultas ' . $fakultas : null,
            $departemen ? 'Departemen ' . $departemen : null,
        ]);

        return implode(' ', $parts);
    }

    private function buildApplicantSummary(Pengajuan $pengajuan): string
    {
        $names = $pengajuan->details
            ->pluck('nama')
            ->filter()
            ->values();

        if ($names->isEmpty()) {
            return '-';
        }

        if ($names->count() === 1) {
            return $names->first();
        }

        if ($names->count() === 2) {
            return $names->join(' dan ');
        }

        return $names->first() . ' dan ' . ($names->count() - 1) . ' calon lainnya';
    }
}