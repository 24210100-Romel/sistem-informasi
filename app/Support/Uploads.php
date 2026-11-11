<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Uploads
{
    /** Simpan ke disk "public" (storage/app/public/uploads) dan kembalikan path relatifnya. */
    public static function store(UploadedFile $file): string
    {
        // Ekstensi tersimpan HANYA dari daftar putih (tidak pernah .php/.phtml dsb., apa pun nama aslinya).
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip'];
        $client = strtolower($file->getClientOriginalExtension());
        $guess = strtolower((string) $file->guessExtension());
        $ext = in_array($client, $allowed, true) ? $client : (in_array($guess, $allowed, true) ? $guess : null);
        if ($ext === null) {
            throw ValidationException::withMessages(['berkas' => 'Tipe berkas tidak diizinkan.']);
        }
        $name = bin2hex(random_bytes(10)).'.'.$ext;

        return $file->storeAs('uploads', $name, 'public');
    }

    /**
     * Titik masuk tunggal untuk berkas dari form admin:
     * 1) terima berkas yang dikirim sebagai teks base64 (tidak memakai folder tmp PHP),
     * 2) pastikan berkas multipart biasa benar-benar diterima PHP.
     */
    public static function prepare(Request $request, string $field, string $label = 'Berkas'): void
    {
        self::absorbEncoded($request, $field);
        self::assertReceived($request, $field, $label);
    }

    /**
     * Browser mengirim berkas sebagai teks base64 (field "<nama>_b64" + "<nama>_b64_name").
     * Teks ikut badan POST biasa, sehingga TIDAK bergantung pada upload_tmp_dir PHP.
     * Berkas ditulis ke storage/app/private/tmp lalu dipasang sebagai UploadedFile biasa,
     * jadi seluruh validasi dan penyimpanan yang ada tetap berlaku tanpa perubahan.
     */
    public static function absorbEncoded(Request $request, string $field): void
    {
        $data = $request->input($field.'_b64');
        if (! is_string($data) || $data === '') {
            return;
        }

        $fail = fn (string $m) => throw ValidationException::withMessages([$field => $m]);

        if (str_contains($data, ',')) { // buang awalan "data:image/jpeg;base64,"
            $data = substr($data, strpos($data, ',') + 1);
        }
        $bytes = base64_decode($data, true);
        if ($bytes === false || $bytes === '') {
            $fail('Berkas rusak saat dikirim. Pilih ulang berkas lalu coba lagi.');
        }

        $name = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $request->input($field.'_b64_name', ''));
        $name = ($name === null || $name === '') ? 'upload' : $name;

        $dir = storage_path('app/private/tmp');
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            $fail('Server tidak dapat membuat folder storage/app/private/tmp. Pastikan folder storage dapat ditulis (chmod -R 775 storage).');
        }
        $tmp = $dir.'/'.bin2hex(random_bytes(8));
        if (@file_put_contents($tmp, $bytes) === false) {
            $fail('Server tidak dapat menulis ke folder storage. Pastikan folder storage dapat ditulis (chmod -R 775 storage).');
        }
        register_shutdown_function(static fn () => @unlink($tmp));

        // Bagian "true" = mode uji: lewati is_uploaded_file() karena berkas ini bukan unggahan multipart.
        $request->files->set($field, new UploadedFile($tmp, $name, null, null, true));
        (function () { $this->convertedFiles = null; })->call($request); // buang cache berkas Laravel
    }

    /**
     * Pastikan berkas benar-benar sampai ke server. Bila PHP menolaknya (mis. melebihi
     * upload_max_filesize), tampilkan alasan sebenarnya, bukan pesan "format salah".
     */
    public static function assertReceived(Request $request, string $field, string $label = 'Berkas'): void
    {
        $file = $request->file($field);
        if (! $file instanceof UploadedFile || $file->isValid()) {
            return;
        }

        $limit = ini_get('upload_max_filesize');
        $msg = match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "$label melebihi batas server PHP (upload_max_filesize = $limit). Kecilkan ukuran berkas atau minta pengelola server menaikkan batasnya.",
            UPLOAD_ERR_PARTIAL => "$label hanya terunggah sebagian. Periksa koneksi lalu coba lagi.",
            UPLOAD_ERR_NO_TMP_DIR => "Folder sementara PHP tidak ada (upload_tmp_dir = ".(ini_get('upload_tmp_dir') ?: sys_get_temp_dir())."). Aktifkan JavaScript dan muat ulang halaman agar $label dikirim lewat jalur alternatif.",
            UPLOAD_ERR_CANT_WRITE => "PHP tidak dapat menulis $label ke disk (disk/kuota penuh atau folder sementara tidak dapat ditulis). Muat ulang halaman agar $label dikirim lewat jalur alternatif.",
            default => "$label gagal diunggah: ".$file->getErrorMessage(),
        };

        throw ValidationException::withMessages([$field => $msg]);
    }

    /** Hapus berkas unggahan baru. Berkas lama (assets/uploads) dan URL luar dibiarkan. */
    public static function delete(?string $path): void
    {
        $path = (string) $path;
        if ($path !== '' && str_starts_with($path, 'uploads/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
