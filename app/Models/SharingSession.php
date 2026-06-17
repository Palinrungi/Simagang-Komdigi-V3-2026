<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SharingSession extends Model
{
    protected $fillable = [
        'created_by',
        'speaker_user_id',
        'moderator_user_id',
        'title',
        'session_date',
        'start_time',
        'location',
        'description',
        'evaluation_form_link',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    /**
     * Admin yang membuat jadwal
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Narasumber
     */
    public function speakerUser()
    {
    return $this->belongsTo(User::class, 'speaker_user_id');
    }

    /**
     * Moderator
     */
    public function moderatorUser()
    {
    return $this->belongsTo(User::class, 'moderator_user_id');
    }
}