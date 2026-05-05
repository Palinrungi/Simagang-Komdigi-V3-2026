<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'position',
        'phone',
        'photo_path',
        'is_active',
        'user_id',
        'team_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function interns()
    {
        return $this->hasMany(Intern::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
}
