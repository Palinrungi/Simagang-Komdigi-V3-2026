<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['team_id', 'name', 'is_active'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}