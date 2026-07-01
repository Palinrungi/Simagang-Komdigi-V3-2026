<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institusi extends Model
{
    use HasFactory;

    protected $table = 'institusis';

    protected $fillable = [
        'user_id',
        'nama_institusi',
        'jenis_institusi',
        'nomor_identitas',
        'no_hp',
        'fakultas',
        'departemen',
        'is_active',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function interns()
    {
        return $this->hasMany(Intern::class);
    }
} 