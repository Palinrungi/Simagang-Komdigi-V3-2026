# 🚀 Knowledge Base: Migrasi Simagang Komdigi ke Laravel 12

**Tujuan:** Panduan komprehensif bagi AI Agent untuk merancang ulang (rebuild) dan menduplikasi sistem "Simagang Komdigi V3" dari Laravel 10 ke proyek **Laravel 12** yang sepenuhnya baru (fresh project).

---

## 1. 🛠️ Tech Stack & Requirements (Target Laravel 12)

*   **Framework:** Laravel 12.x
*   **PHP Version:** PHP 8.2 atau 8.3 (Minimum untuk Laravel 12)
*   **Database:** MySQL (InnoDB)
*   **Frontend:** Blade Templates + Vite + Vanilla CSS / Tailwind CSS (menyesuaikan UI yang ada)
*   **Authentication:** Laravel Breeze / UI (disesuaikan) + Laravel Sanctum (untuk API jika ada)
*   **Authorization:** `spatie/laravel-permission` (Wajib, untuk manajemen role & permission)
*   **Media/File Handling:** `intervention/image` (Versi 3.x), Local Storage (Public)
*   **Export/Import:** `maatwebsite/excel`
*   **PDF Generation:** `setasign/fpdi` & `tecnickcom/tcpdf`

---

## 2. 👥 Sistem Role & Autorisasi (Spatie)

Sistem menggunakan `spatie/laravel-permission` secara eksklusif. **PENTING:** Jangan menggunakan dual-role system (misal kolom `role` di tabel `users`). Gunakan fungsi Spatie 100%.

**Daftar Role Utama:**
1.  `super_admin`: Akses penuh, manajemen akun, RAG Knowledge.
2.  `admin`: Pengelola harian magang, verifikasi lowongan, pembagian tim, monitoring.
3.  `mentor`: Membimbing intern, cek logbook, absensi, report, dan memberi nilai sertifikat.
4.  `intern`: Siswa/mahasiswa magang. Mengisi logbook, absensi, submission microskill.
5.  `institusi`: Pihak sekolah/kampus. Memantau absensi, logbook, dan mendaftarkan pengajuan magang.
6.  `industri`: Pihak perusahaan mitra. Membuka lowongan magang.

---

## 3. 🗄️ Arsitektur Database & Model Utama

Sistem ini memiliki entitas yang saling terkait erat. Berikut adalah arsitektur yang harus dibangun di Laravel 12, dengan **perbaikan dari versi sebelumnya**.

### Entitas Inti (Core)
*   **User:** Tabel core otentikasi. (Kolom: id, name, email, password). Semua data identitas utama (nama, email) **hanya** disimpan di sini untuk mencegah duplikasi.
*   **Intern:** Profil peserta magang. Berelasi 1-1 dengan `User`. **Jangan** menduplikasi kolom `name`.
*   **Mentor:** Profil pembimbing. Berelasi 1-1 dengan `User`.
*   **Team:** Kelompok magang. Mengelompokkan Mentor dan Interns.
*   **Institusi & Industri:** Profil entitas mitra kerja. Berelasi 1-1 dengan `User`.

### Entitas Operasional (Modul Magang)
*   **Lowongan:** Dibuat oleh Industri, diverifikasi oleh Admin.
*   **Pengajuan & PengajuanDetail:** Pendaftaran magang oleh Institusi/Industri. `PengajuanDetail` memuat data kandidat intern.
*   **Attendance (Absensi):** Rekam jejak kehadiran harian intern. (Perlu index di `intern_id` dan `date`).
*   **Logbook:** Laporan kegiatan harian intern yang diapprove oleh Mentor.
*   **MicroSkill & MicroSkillSubmission:** Modul pembelajaran skill khusus.
*   **FinalReport & Testimonial:** Laporan akhir masa magang.
*   **Certificate & CertificateScore:** Pembangkitan sertifikat digital otomatis berdasarkan nilai.

### Entitas Publik/Landing Page
*   **ActivityPost:** Artikel & Video Youtube untuk landing page.
*   **SharingSession:** Jadwal webinar/sharing session mingguan.

---

## 4. 🚨 Lesson Learned: Kesalahan V3 yang HARUS Dihindari di V12

Saat membangun schema migration dan eloquent model di Laravel 12, AI Agent **WAJIB** mematuhi aturan berikut yang diambil dari audit sistem lama:

1.  **Single Source of Truth untuk Nama & Email (ANTI-DUPLIKASI):**
    *   Tabel `interns` dan `pengajuan_details` **TIDAK BOLEH** menyimpan kolom `name` dan `email` jika sudah terdaftar. Ambil selalu dari `$intern->user->name`.
    *   Jika mendaftarkan Intern baru dari Pengajuan, sistem harus mengecek: *Apakah email sudah ada di tabel users?* Jika ya, kaitkan ID-nya. Jika tidak, buat `User` baru. (Mencegah bug *email is already taken*).
2.  **Gunakan Spatie Permission Sepenuhnya:**
    *   Hapus kolom statis string `role` di tabel `users`.
    *   Gunakan `$user->hasRole('admin')` atau policy/middleware Spatie.
3.  **Tabel Interns yang Ramping (Normalized):**
    *   Jangan simpan `institution`, `purpose`, `start_date`, `end_date` langsung di tabel `interns`.
    *   Ambil data tersebut melalui rantai relasi: `$intern->pengajuanDetail->pengajuan->...`
4.  **Wajib Foreign Key Indexing (PERFORMA):**
    *   Semua kolom berakhiran `_id` di migration WAJIB diberikan `$table->index('nama_kolom_id')` atau didefinisikan sebagai `$table->foreignId('...')->constrained()`. Di sistem lama, lebih dari 11 FK tidak memiliki index yang menyebabkan query lambat.
5.  **Relasi Eloquent yang Bersih:**
    *   Pastikan definisi relasi (hasOne, hasMany) masuk akal. Contoh: Jika `FinalReport` hanya memiliki satu `Testimonial`, gunakan `hasOne`, jangan campur dengan `hasMany` di model yang sama.
6.  **Migration Squash:**
    *   Sistem lama memiliki 67 file migration hasil tambal sulam. Untuk versi Laravel 12, buat 1 skema utuh (1 file migration per tabel) agar rapi.

---

## 5. 🗺️ Struktur Routing (Garis Besar)

Gunakan fitur baru Laravel 11/12 (`bootstrap/app.php` untuk middleware).

*   `routes/web.php`
    *   `GET /` (Landing Page, Artikel, Lowongan Publik, Testimoni)
    *   `GET /daftar-lowongan`, `/sharing-session`
    *   `Auth Routes` (Login, Register Institusi/Industri, Forgot Password)
    *   **Prefix /admin:** (Middleware: `auth`, `role:admin|super_admin`) -> Dashboard, Intern, Mentor, Lowongan, Pengajuan, Logbook, Attendance, Monitoring.
    *   **Prefix /mentor:** (Middleware: `auth`, `role:mentor`) -> Dashboard, Approval Logbook, Nilai Report, Attendance anak didik.
    *   **Prefix /intern:** (Middleware: `auth`, `role:intern`) -> Dashboard, Isi Logbook, Isi Absensi harian, Kumpul MicroSkill, Final Report.
    *   **Prefix /institusi:** (Middleware: `auth`, `role:institusi`) -> Buat Pengajuan, Monitor anak didik.
    *   **Prefix /industri:** (Middleware: `auth`, `role:industri`) -> Buka Lowongan, Monitor pelamar.

---

## 6. 🤖 AI Agent Workflow untuk Membuat Proyek Baru

1.  **Inisiasi:** `composer create-project laravel/laravel simagang-v12`
2.  **Konfigurasi Dasar:** Setup `.env`, integrasi MySQL.
3.  **Instalasi Dependencies:** Install Spatie, Intervention Image 3, Excel, TCPDF.
4.  **Skema & Migrasi:** Buat migrasi berdasarkan ERD (Entity Relationship Diagram) yang sudah dinormalisasi (merujuk poin 4). Pastikan `constrained()` dan index terpasang.
5.  **Model & Relasi:** Buat model Eloquent. Hindari relasi fiktif.
6.  **Autentikasi & Role:** Setup Seeder untuk Spatie Role dan super admin.
7.  **Controller & View:** Migrasi pelan-pelan logic dari controller lama (V3) ke controller baru (V12) dengan best practice PHP 8.2 (Typed properties, readonly classes jika perlu, match expression).

> **Dokumen ini adalah pedoman mutlak bagi AI untuk memastikan iterasi Laravel 12 terhindar dari *tech-debt* (hutang teknis) yang ada di versi sebelumnya.**
