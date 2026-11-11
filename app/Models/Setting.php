<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $primaryKey = 'k';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['k', 'v'];

    public const FIELDS = [
        'nama_situs' => 'Nama situs',
        'deskripsi' => 'Deskripsi singkat',
        'alamat' => 'Alamat',
        'email' => 'Email',
        'telepon' => 'Nomor telepon',
        'fax' => 'Fax',
        'tentang_judul' => 'Tentang Kami (beranda): judul',
        'tentang_isi' => 'Tentang Kami (beranda): ringkasan',
        'ai_aktif' => 'Asisten virtual (tombol AI di website)',
    ];

    public const TEXTAREA = ['alamat', 'deskripsi', 'tentang_isi'];

    /** @return array<string, string|null> */
    public static function allKeyed(): array
    {
        return static::query()->pluck('v', 'k')->all();
    }
}
