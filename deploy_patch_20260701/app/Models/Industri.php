<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nama_industri',
        'logo_industri',
        'bidang_industri',
        'deskripsi_industri',
        'alamat_industri',
        'kota_kabupaten',
        'email_industri',
        'nomor_telepon_industri',
        'nib',
        'status',
        'jenis_lembaga',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lowongans()
    {
        return $this->hasMany(Lowongan::class);
    }
}
