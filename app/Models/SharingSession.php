<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SharingSession extends Model
{
    public const EVALUATION_FORM_LINK = 'https://s.komdigi.go.id/Sharingsession-magang';

    protected $fillable = [
        'created_by',
        'speaker_user_id',
        'moderator_user_id',
        'speaker',
        'moderator',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function speakerUser()
    {
        return $this->belongsTo(User::class, 'speaker_user_id');
    }

    public function moderatorUser()
    {
        return $this->belongsTo(User::class, 'moderator_user_id');
    }

    public function getSpeakerNameAttribute()
    {
        return $this->speakerUser?->intern?->name
            ?? $this->speakerUser?->name
            ?? $this->speaker
            ?? '-';
    }

    public function getModeratorNameAttribute()
    {
        return $this->moderatorUser?->intern?->name
            ?? $this->moderatorUser?->name
            ?? $this->moderator
            ?? '-';
    }

    public function getDocumentationPhotoUrlAttribute()
    {
        return $this->documentation_photo
            ? asset('storage/' . $this->documentation_photo)
            : null;
    }

    public function getEvaluationFormLinkAttribute($value)
    {
        return self::EVALUATION_FORM_LINK;
    }

    public function getEvaluationOpensAtAttribute()
    {
        if (!$this->session_date) {
            return null;
        }

        $date = Carbon::parse($this->session_date)->format('Y-m-d');
        $time = $this->start_time
            ? Carbon::parse($this->start_time)->format('H:i:s')
            : '00:00:00';

        return Carbon::parse($date . ' ' . $time);
    }

    public function getEvaluationClosesAtAttribute()
    {
        if (!$this->session_date) {
            return null;
        }

        return Carbon::parse($this->session_date)->endOfDay();
    }

    public function getEvaluationIsOpenAttribute()
    {
        if (!$this->evaluation_opens_at || !$this->evaluation_closes_at) {
            return false;
        }

        return now()->between(
            $this->evaluation_opens_at,
            $this->evaluation_closes_at
        );
    }

    public function getEvaluationAlreadyClosedAttribute()
    {
        if (!$this->evaluation_closes_at) {
            return false;
        }

        return now()->gt($this->evaluation_closes_at);
    }

    public function getMaterialStatusAttribute()
    {
        if (
            empty($this->title) &&
            empty($this->description)
        ) {
            return 'belum';
        }

        if (
            empty($this->title) ||
            empty($this->description)
        ) {
            return 'belum_lengkap';
        }

        return 'lengkap';
    }

    public function getIsSpeakerAttribute()
    {
        return auth()->check() && (int) $this->speaker_user_id === (int) auth()->id();
    }

    public function getIsModeratorAttribute()
    {
        return auth()->check() && (int) $this->moderator_user_id === (int) auth()->id();
    }
}