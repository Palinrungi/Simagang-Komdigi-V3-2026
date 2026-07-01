# 🔍 Analisis Database Simagang V3

**Tanggal:** 1 Juli 2026  
**Analyst:** Database Expert Review  
**Sistem:** Simagang Komdigi V3 — Sistem Informasi Magang  
**Database:** MySQL (InnoDB) via Laravel Eloquent ORM  

---

## Ringkasan Eksekutif

Setelah menganalisis seluruh **21 model Eloquent**, **67 file migration**, dan skema database lengkap, kesimpulan umum:

> ⚠️ Database ini **fungsional dan berjalan**, tetapi memiliki beberapa masalah desain fundamental yang menyebabkan **inkonsistensi data, duplikasi, query lambat, dan bug** (termasuk kasus "email is already taken" yang sudah terjadi di production).

### Skor Penilaian

| Aspek | Skor | Keterangan |
|-------|------|------------|
| Normalisasi Data | ⭐⭐ (2/5) | Banyak duplikasi kolom antar tabel |
| Integritas Referensial | ⭐⭐⭐ (3/5) | FK ada tapi tidak lengkap |
| Indexing & Performa | ⭐⭐ (2/5) | 11+ FK tanpa index |
| Konsistensi Model | ⭐⭐ (2/5) | Relasi duplikat dan fiktif |
| Migration Hygiene | ⭐⭐ (2/5) | 67 migration, banyak tambalan kecil |
| Keamanan & Constraint | ⭐⭐⭐⭐ (4/5) | Unique constraint dan validasi cukup baik |

---

## Daftar Tabel yang Dianalisis

| # | Tabel | Jumlah Kolom | Foreign Keys | Index |
|---|-------|-------------|-------------|-------|
| 1 | `users` | 9 | 0 | 2 (PK + email unique) |
| 2 | `interns` | 17 | 4 (tanpa index!) | 1 (PK saja) |
| 3 | `mentors` | 10 | 2 (tanpa index!) | 1 (PK saja) |
| 4 | `teams` | 4 | 0 | 2 (PK + name unique) |
| 5 | `attendances` | 13 | 1 (tanpa index!) | 1 (PK saja) |
| 6 | `logbooks` | 10 | 2 (tanpa index!) | 1 (PK saja) |
| 7 | `final_reports` | 16 | 1 (tanpa index!) | 1 (PK saja) |
| 8 | `micro_skill_submissions` | 13 | 1 | 3 ✅ |
| 9 | `micro_skills` | 5 | 0 | 1 (PK) |
| 10 | `certificates` | 5 | 1 (tanpa index!) | 1 (PK saja) |
| 11 | `certificate_scores` | 9 | 1 (tanpa index!) | 1 (PK saja) |
| 12 | `pengajuans` | 14 | 2 | 3 ✅ |
| 13 | `pengajuan_details` | 11 | 1 | 2 ✅ |
| 14 | `institusis` | 10 | 1 (tanpa index!) | 1 (PK saja) |
| 15 | `industris` | 14 | 1 (tanpa index!) | 1 (PK saja) |
| 16 | `lowongans` | 14 | 2 (tanpa index!) | 1 (PK saja) |
| 17 | `testimonials` | 6 | 2 | 3 ✅ |
| 18 | `sharing_sessions` | 12 | 3 | 4 ✅ |
| 19 | `activity_posts` | 11 | — | 1 (PK) |
| 20 | `page_views` | 5 | 0 | 2 |
| 21 | `system_settings` | 5 | 0 | 2 |

---

## 1. 🔴 MASALAH KRITIS — Penyebab Bug

### 1.1 Data Duplikat: Nama & Email Tersimpan di 3 Tempat

```
┌──────────────────────┐
│      users           │
│  - name ◄────────┐   │
│  - email ◄───────┤   │
└──────────────────┤───┘
                   │ DUPLIKAT!
┌──────────────────┤───┐
│      interns         │
│  - name ◄────────┤   │  (copy dari users.name)
│  - institution   │   │  (copy dari pengajuan → institusi)
│  - purpose       │   │  (copy dari pengajuan.keperluan)
│  - start_date    │   │  (copy dari pengajuan.start_date)
│  - end_date      │   │  (copy dari pengajuan.end_date)
└──────────────────┤───┘
                   │ DUPLIKAT!
┌──────────────────┤───┐
│ pengajuan_details    │
│  - nama ◄────────┘   │  (copy lagi!)
│  - email ◄───────┘   │  (copy lagi!)
└──────────────────────┘
```

**Apa yang salah:**
- `name` disimpan di `users`, `interns`, DAN `pengajuan_details` — jika diubah di satu tempat, 2 tempat lain jadi *stale*
- `email` ada di `users` (untuk login) dan `pengajuan_details` (untuk pendaftaran) — tidak ada mekanisme sinkronisasi

**Dampak nyata:**  
Ini adalah **akar penyebab bug "email is already taken"** di production. Alurnya:
1. Siswa register sendiri → email masuk ke `users`
2. Admin sekolah juga mendaftarkan siswa yang sama via pengajuan → email masuk ke `pengajuan_details`
3. Saat admin Komdigi ingin membuat akun intern → email auto-fill dari `pengajuan_details` → bentrok dengan `users` yang sudah ada

**Rekomendasi:**
- `interns.name` → **HAPUS**, ambil dari `users.name` via relasi `$intern->user->name`
- Buat mekanisme cek: jika email sudah ada di `users`, gunakan user yang ada, jangan create baru

---

### 1.2 Sistem Role Ganda (Dual Role System)

```
┌─ SUMBER ROLE 1 ──────────┐     ┌─ SUMBER ROLE 2 ──────────────────┐
│ users.role = 'intern'     │     │ model_has_roles (Spatie)          │
│                           │     │  user_id=30, role_id=5 (intern)   │
└───────────────────────────┘     └───────────────────────────────────┘
```

**Apa yang salah:**  
Setiap pengecekan role di `User.php` harus cek **dua sumber** sekaligus:

```php
// User.php - setiap method cek 2x:
public function isAdmin() {
    return $this->hasAnyRole([...])           // Spatie
        || in_array($this->role, [...]);       // Kolom biasa
}

public function isIntern() {
    return $this->hasRole('intern')           // Spatie
        || $this->role === 'intern';           // Kolom biasa
}
```

**Risiko:**
- Bisa terjadi `users.role = 'intern'` tapi Spatie belum di-assign → atau sebaliknya
- Developer baru bisa bingung harus update yang mana
- Double maintenance setiap kali ada role baru

**Rekomendasi:**  
Pilih **SATU** saja:
- **Opsi A (Rekomendasi):** Hapus `users.role`, gunakan Spatie 100%
- **Opsi B:** Hapus Spatie, gunakan `users.role` saja (tapi kehilangan fitur permission granular)

---

## 2. 🟠 MASALAH DESAIN — Struktur Relasi

### 2.1 Tabel `interns` Terlalu Gemuk (Fat Table)

Tabel `interns` punya **17 kolom**, di mana beberapa seharusnya tidak perlu ada:

| Kolom di `interns` | Duplikat dari | Relasi yang benar |
|---|---|---|
| `name` | `users.name` | `$intern->user->name` |
| `institution` | `institusi.nama_institusi` | `$intern->pengajuanDetail->pengajuan->institusi->nama_institusi` |
| `purpose` | `pengajuan.keperluan` | `$intern->pengajuanDetail->pengajuan->keperluan` |
| `start_date` | `pengajuan.start_date` | `$intern->pengajuanDetail->pengajuan->start_date` |
| `end_date` | `pengajuan.end_date` | `$intern->pengajuanDetail->pengajuan->end_date` |
| `team_id` | `mentors.team_id` | `$intern->mentor->team_id` |

> **Catatan:** Menyimpan `team_id` di `interns` secara terpisah dari `mentors.team_id` berarti jika admin pindahkan mentor ke tim lain, intern-nya **tidak otomatis ikut pindah**. Ini bisa menyebabkan data tidak konsisten.

### 2.2 Relasi Fiktif: `Institusi::interns()`

File: `app/Models/Institusi.php`
```php
public function interns() {
    return $this->hasMany(Intern::class);  // ❌ TIDAK AKAN BEKERJA!
}
```

**Masalah:** Tabel `interns` **tidak memiliki kolom `institusi_id`**. Relasi ini akan selalu menghasilkan query error atau result kosong.

**Relasi yang benar** (melalui chain):
```
Institusi → Pengajuan → PengajuanDetail → Intern
```

Atau menggunakan `hasManyThrough` / custom relationship.

### 2.3 Relasi Duplikat di Model `Intern`

File: `app/Models/Intern.php`
```php
public function team() {
    return $this->belongsTo(Team::class);         // Relasi 1
}
public function teamRelation() {
    return $this->belongsTo(Team::class, 'team_id');  // Relasi 2 (IDENTIK!)
}
```

Kedua method melakukan **hal yang persis sama** — `belongsTo(Team::class)` secara default sudah menggunakan `team_id`.

### 2.4 Relasi Ambigu di Model `FinalReport`

File: `app/Models/FinalReport.php`
```php
public function testimonial()  { return $this->hasOne(Testimonial::class); }   // Satu
public function testimonials() { return $this->hasMany(Testimonial::class); }   // Banyak
```

**Masalah:** Memiliki `hasOne` dan `hasMany` ke tabel yang sama menunjukkan ketidakjelasan desain. Apakah 1 final report punya 1 testimonial atau banyak? Pilih salah satu.

### 2.5 Relasi `Intern::finalReport()` vs `Intern::finalReports()`

File: `app/Models/Intern.php`
```php
public function finalReport()  { return $this->hasOne(FinalReport::class); }
public function finalReports() { return $this->hasMany(FinalReport::class, 'intern_id'); }
```

Masalah yang sama — apakah 1 intern punya 1 atau banyak final report?

---

## 3. 🟡 MASALAH PERFORMA — Index yang Hilang

### 3.1 Foreign Key Columns Tanpa Index

Setiap kolom FK yang tidak memiliki index akan menyebabkan **full table scan** saat melakukan JOIN atau WHERE.

```
❌ TANPA INDEX (harus ditambahkan):

interns.user_id              → Dipakai saat: User::with('intern')
interns.mentor_id            → Dipakai saat: Mentor::with('interns'), filter by mentor
interns.team_id              → Dipakai saat: Team::with('interns'), filter by team
interns.pengajuan_detail_id  → Dipakai saat: link ke pengajuan
mentors.user_id              → Dipakai saat: User::with('mentor')
mentors.team_id              → Dipakai saat: Team::with('mentors')
logbooks.intern_id           → Dipakai saat: Intern::with('logbooks')
logbooks.approved_by         → Dipakai saat: filter logbook by mentor
attendances.intern_id        → Dipakai saat: Intern::with('attendances')
final_reports.intern_id      → Dipakai saat: Intern::with('finalReport')
certificates.intern_id       → Dipakai saat: Intern::with('certificate')
certificate_scores.certificate_id → Dipakai saat: Certificate::with('score')
institusis.user_id           → Dipakai saat: User::with('institusi')
industris.user_id            → Dipakai saat: User::with('industri')
lowongans.industri_id        → Dipakai saat: Industri::with('lowongans')
lowongans.team_id            → Dipakai saat: filter lowongan by team
```

### 3.2 Kolom Filter Tanpa Index

Kolom-kolom berikut sering dipakai dalam `WHERE` clause tapi tidak ada index:

| Tabel | Kolom | Contoh penggunaan |
|-------|-------|-------------------|
| `interns` | `is_active` | Memisahkan intern aktif vs alumni di setiap halaman |
| `attendances` | `date` | Filter absensi per hari/bulan |
| `attendances` | `status` | Hitung hadir/izin/alfa |
| `logbooks` | `date` | Filter logbook per tanggal |
| `pengajuans` | `status` | Filter pending/approved/rejected |
| `mentors` | `is_active` | Filter mentor aktif |

### 3.3 Estimasi Dampak

Dengan jumlah data saat ini (puluhan record), dampaknya belum terasa. Tapi jika berkembang:

| Jumlah Intern | Tanpa Index | Dengan Index |
|--------------|-------------|-------------|
| 50 | ~2ms | ~1ms |
| 500 | ~20ms | ~1ms |
| 5,000 | ~200ms | ~2ms |
| 50,000 | ~2 detik | ~3ms |

---

## 4. 🟡 MASALAH MIGRATION — Terlalu Banyak Tambalan

### 4.1 Evolusi Tabel `interns` (7 kali modifikasi!)

```
2025-11-03  create_interns_table           → Tabel awal
2025-11-03  add_mentor_id_to_interns       → +mentor_id
2025-11-04  add_team_to_interns            → +team (string, kemudian dihapus?)
2025-11-07  rename_student_id_to_phone     → rename kolom
2025-11-07  add_purpose_to_interns         → +purpose
2026-03-03  add_team_id_to_interns         → +team_id (FK, menggantikan team string?)
2026-05-05  add_pengajuan_detail_id        → +pengajuan_detail_id
2026-05-21  add_soft_hard_skills_to_interns → +soft_skill, +hard_skill
```

### 4.2 Evolusi Tabel `pengajuans` (4 kali modifikasi!)

```
2026-04-14  create_pengajuans_table        → Tabel awal
2026-04-15  add_fields_to_pengajuans       → +kolom tambahan
2026-04-21  add_admin_note                 → +admin_note
2026-04-28  add_fields_to_pengajuans       → +kolom tambahan lagi
2026-05-25  add_lowongan_id               → +lowongan_id
2026-06-03  add_lowongan_id (lagi?)        → modifikasi lowongan_id
```

**Rekomendasi:** Saat melakukan fresh install, squash semua migration menjadi satu per tabel untuk kebersihan.

---

## 5. 🟢 HAL YANG SUDAH BAIK

| Aspek | Status | Keterangan |
|-------|--------|------------|
| FK Constraint `pengajuan_details` → `pengajuans` | ✅ | Pakai `ON DELETE CASCADE` — hapus pengajuan otomatis hapus detail |
| Unique constraint `users.email` | ✅ | Mencegah duplikat email di tabel users |
| Unique constraint `teams.name` | ✅ | Mencegah duplikat nama tim |
| Unique composite `micro_skill_submissions(intern_id, title)` | ✅ | Mencegah duplikat submission per intern |
| Index pada `micro_skill_submissions(intern_id, status)` | ✅ | Query filter submission efisien |
| Spatie Permission system | ✅ | Struktur role/permission fleksibel dan scalable |
| Relasi Eloquent umumnya benar | ✅ | BelongsTo/HasMany/HasOne tepat sasaran |
| Tabel `pengajuans` punya FK index | ✅ | `institusi_id` dan `lowongan_id` ter-index |
| Cast type di model (date, boolean, array) | ✅ | Data handling yang benar |
| Polymorphic index di Spatie tables | ✅ | `model_id + model_type` ter-index |

---

## 6. ENTITY RELATIONSHIP DIAGRAM (Logis)

```
┌─────────┐     ┌──────────┐     ┌──────────────┐
│  users  │────►│ mentors  │────►│    teams      │
│         │     └──────────┘     └──────────────┘
│         │          │                   │
│         │          ▼                   │
│         │     ┌──────────┐             │
│         │────►│ interns  │◄────────────┘
│         │     └──────────┘
│         │          │
│         │     ┌────┼────────────┬──────────────┐
│         │     ▼    ▼            ▼              ▼
│         │  attend  logbooks  final_reports  micro_skill_
│         │  ances                            submissions
│         │                       │
│         │                       ▼
│         │              ┌──────────────┐     ┌──────────────┐
│         │              │ certificates │────►│ cert_scores  │
│         │              └──────────────┘     └──────────────┘
│         │
│         │     ┌──────────┐     ┌──────────────┐
│         │────►│institusi │────►│ pengajuans   │
│         │     └──────────┘     └──────┬───────┘
│         │                            │
│         │                            ▼
│         │                     ┌──────────────┐
│         │                     │pengajuan_    │
│         │                     │details       │───► interns (via FK)
│         │                     └──────────────┘
│         │
│         │     ┌──────────┐     ┌──────────────┐
│         │────►│ industri │────►│  lowongans   │───► pengajuans (via FK)
│         │     └──────────┘     └──────────────┘
└─────────┘
```

---

## 7. REKOMENDASI PRIORITAS

### 🔴 Prioritas 1 — Harus Diperbaiki (Penyebab Bug Aktif)

| # | Aksi | Dampak |
|---|------|--------|
| 1.1 | Hilangkan `interns.name`, ambil dari `users.name` via relasi | Menghilangkan data tidak sinkron |
| 1.2 | Hapus `users.role`, gunakan Spatie 100% | Menghilangkan dual role system |
| 1.3 | Pada `AdminInternController::store()`, cek apakah email sudah ada di `users` sebelum create user baru | Menyelesaikan bug "email is already taken" |

### 🟠 Prioritas 2 — Performa & Stabilitas

| # | Aksi | Dampak |
|---|------|--------|
| 2.1 | Tambahkan index pada semua FK yang belum ada (16 kolom) | Query 10-100x lebih cepat saat data besar |
| 2.2 | Tambahkan composite index `interns(is_active, mentor_id)` | Halaman monitoring lebih cepat |
| 2.3 | Tambahkan index `attendances(intern_id, date)` | Filter absensi lebih cepat |
| 2.4 | Hapus relasi fiktif `Institusi::interns()` | Mencegah error di masa depan |
| 2.5 | Hapus duplikat `Intern::teamRelation()` | Clean up kode |

### 🟡 Prioritas 3 — Normalisasi & Clean Up

| # | Aksi | Dampak |
|---|------|--------|
| 3.1 | Pertimbangkan hapus `interns.institution/purpose/start_date/end_date` — ambil dari relasi `pengajuanDetail` | Normalisasi data |
| 3.2 | Squash migration files menjadi 1 per tabel | Lebih mudah dibaca dan di-maintain |
| 3.3 | Tentukan: `FinalReport` hasOne atau hasMany `Testimonial`? Hapus yang tidak dipakai | Kejelasan desain |

---

## 8. CATATAN PENTING

> ⚠️ **Jangan langsung eksekusi perubahan Prioritas 1 dan 3 di production!**
> 
> Perubahan ini akan berdampak pada banyak file controller dan view yang mengakses `$intern->name` secara langsung. Perlu dilakukan:
> 1. Mapping semua file yang terpengaruh
> 2. Update query secara bertahap
> 3. Testing menyeluruh di environment lokal
> 4. Backup database production sebelum deploy

> ✅ **Prioritas 2 (penambahan index) relatif aman** untuk dieksekusi langsung karena hanya menambahkan index tanpa mengubah data atau logic.

---

*Report ini dibuat berdasarkan analisis kode sumber dan skema database Simagang V3 pada 1 Juli 2026.*
