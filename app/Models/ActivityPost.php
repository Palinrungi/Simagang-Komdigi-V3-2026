<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityPost extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'slug',
        'type',
        'thumbnail',
        'excerpt',
        'content',
        'youtube_url',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function getThumbnailUrlAttribute(): string
    {
        // Khusus YouTube: selalu ambil thumbnail dari YouTube
        if ($this->type === 'youtube' && $this->youtube_id) {
            return 'https://i.ytimg.com/vi/' . $this->youtube_id . '/hqdefault.jpg';
        }

        // Khusus artikel: pakai thumbnail upload
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        return asset('storage/photos-landingpage/default-news.jpg');
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_id) {
            return null;
        }

        return 'https://www.youtube.com/embed/' . $this->youtube_id;
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }

        $url = trim($this->youtube_url);

        // Format: https://youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Format: https://www.youtube.com/watch?v=VIDEO_ID
        $query = parse_url($url, PHP_URL_QUERY);

        if ($query) {
            parse_str($query, $params);

            if (!empty($params['v'])) {
                return $params['v'];
            }
        }

        // Format: /shorts/VIDEO_ID
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Format: /embed/VIDEO_ID
        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Format: /live/VIDEO_ID
        if (preg_match('/youtube\.com\/live\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'youtube' ? 'YouTube' : 'Artikel';
    }

    public function getTypeIconAttribute(): string
    {
        return $this->type === 'youtube' ? 'fab fa-youtube' : 'fas fa-newspaper';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::makeUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::makeUniqueSlug($post->title, $post->id);
            }
        });
    }

    private static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}