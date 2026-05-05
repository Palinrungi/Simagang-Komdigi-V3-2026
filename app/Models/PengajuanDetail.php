<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'pengajuan_id',
        'nama',
        'jurusan',
        'email',
        'no_telp',
        'jenis_kelamin',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function intern()
    {
        return $this->hasOne(Intern::class);
    }
}
