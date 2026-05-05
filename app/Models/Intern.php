<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intern extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'education_level',
        'major',
        'phone',
        'institution',
        'purpose',
        'mentor_id',
        'team',
        'start_date',
        'end_date',
        'photo_path',
        'is_active',
        'team_id',
        'pengajuan_detail_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    public function finalReport()
    {
        return $this->hasOne(FinalReport::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function microSkills()
    {
        return $this->hasMany(MicroSkillSubmission::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function finalReports()
    {
        return $this->hasMany(FinalReport::class, 'intern_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function pengajuanDetail()
    {
        return $this->belongsTo(PengajuanDetail::class);
    }
    
}