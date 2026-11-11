<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['jenis', 'judul', 'slug', 'isi', 'tautan', 'berkas', 'status'];

    public const JENIS = [
        'dokumen' => 'Dokumen',
        'halaman' => 'Halaman',
        'pengumuman' => 'Pengumuman',
        'slider' => 'Slider',
        'sumber' => 'Sumber',
    ];

    public function scopeOfType(Builder $q, string $jenis): Builder
    {
        return $q->where('jenis', $jenis);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published');
    }

    /** Halaman terbit dengan slug tepat. */
    public static function pageBySlug(string $slug): ?self
    {
        return static::ofType('halaman')->published()->where('slug', $slug)->orderByDesc('id')->first();
    }
}
