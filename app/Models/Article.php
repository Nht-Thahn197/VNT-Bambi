<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'create_at',
        'status',
        'user_id',
        'category_id',
    ];

    public $timestamps = false;

    protected $casts = [
        'create_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content ?? ''), 160);
    }

    public function getReadingTimeAttribute(): string
    {
        $text = trim(strip_tags($this->content ?? ''));
        if ($text === '') {
            return '1 phút đọc';
        }

        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $count = is_array($words) ? count($words) : 0;
        $minutes = max(1, (int) ceil($count / 220));

        return $minutes . ' phút đọc';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Xuất bản' : 'Nháp';
    }
}
