<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'image', 'category_id', 'author_id', 'status', 'is_featured'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Berita terbit, terbaru lebih dulu. */
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()->orderByDesc('created_at')->orderByDesc('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return Media::url($this->image);
    }

    public function excerpt(int $limit = 150): string
    {
        return Media::excerpt((string) $this->content, $limit);
    }

    /**
     * Jadikan satu-satunya Berita Utama (atau kosongkan bila $id null).
     */
    public static function setFeatured(?int $id): void
    {
        static::query()->where('is_featured', true)->update(['is_featured' => false]);
        if ($id) {
            static::query()->whereKey($id)->update(['is_featured' => true]);
        }
    }
}
