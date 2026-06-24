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
        'documentation_photo',
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
     * Narasumber dari relasi user
     */
    public function speakerUser()
    {
        return $this->belongsTo(User::class, 'speaker_user_id');
    }

    /**
     * Moderator dari relasi user
     */
    public function moderatorUser()
    {
        return $this->belongsTo(User::class, 'moderator_user_id');
    }

    /**
     * URL dokumentasi foto
     */
    public function getDocumentationPhotoUrlAttribute()
    {
        return $this->documentation_photo
            ? asset('storage/' . $this->documentation_photo)
            : null;
    }

    /**
     * Status kelengkapan materi
     */
    public function getMaterialStatusAttribute()
    {
        if (
            empty($this->title) &&
            empty($this->description) &&
            empty($this->evaluation_form_link)
        ) {
            return 'belum';
        }

        if (
            empty($this->title) ||
            empty($this->description) ||
            empty($this->evaluation_form_link)
        ) {
            return 'belum_lengkap';
        }

        return 'lengkap';
    }
}