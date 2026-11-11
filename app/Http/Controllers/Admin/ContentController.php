<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Support\HtmlSanitizer;
use App\Support\MenuLink;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function index(Request $request, string $jenis)
    {
        $items = Content::ofType($jenis)->orderByDesc('created_at')->orderByDesc('id')->get();

        return view('admin.contents', [
            'jenis' => $jenis,
            'label' => Content::JENIS[$jenis],
            'items' => $items,
            'edit' => $items->firstWhere('id', (int) $request->query('edit')),
            'useFile' => $this->useFile($jenis),
            'useBody' => $this->useBody($jenis),
            'allowExt' => $this->allowedExtensions($jenis),
        ]);
    }

    public function store(Request $request, string $jenis): RedirectResponse
    {
        $data = $this->validated($request, $jenis);
        if ($request->hasFile('berkas')) {
            $data['berkas'] = Uploads::store($request->file('berkas'));
        }
        Content::create($data + ['jenis' => $jenis]);

        return $this->done($jenis, 'berhasil ditambahkan.');
    }

    public function update(Request $request, string $jenis, Content $content): RedirectResponse
    {
        abort_unless($content->jenis === $jenis, 404);

        $data = $this->validated($request, $jenis);
        if ($request->hasFile('berkas')) {
            Uploads::delete($content->berkas);
            $data['berkas'] = Uploads::store($request->file('berkas'));
        }
        $content->update($data);

        return $this->done($jenis, 'berhasil diperbarui.');
    }

    public function destroy(string $jenis, Content $content): RedirectResponse
    {
        abort_unless($content->jenis === $jenis, 404);

        Uploads::delete($content->berkas);
        $content->delete();

        return $this->done($jenis, 'berhasil dihapus.');
    }

    private function done(string $jenis, string $message): RedirectResponse
    {
        return redirect()->route('admin.konten.index', $jenis)->with('success', Content::JENIS[$jenis].' '.$message);
    }

    private function useFile(string $jenis): bool
    {
        return in_array($jenis, ['dokumen', 'slider'], true);
    }

    private function useBody(string $jenis): bool
    {
        return in_array($jenis, ['halaman', 'pengumuman'], true);
    }

    /** @return array<int, string> */
    private function allowedExtensions(string $jenis): array
    {
        return $jenis === 'slider'
            ? ['jpg', 'jpeg', 'png', 'webp']
            : ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'jpg', 'jpeg', 'png'];
    }

    private function validated(Request $request, string $jenis): array
    {
        Uploads::prepare($request, 'berkas', 'Berkas');

        $request->merge([
            'judul' => trim((string) $request->input('judul')),
            'tautan' => trim((string) $request->input('tautan')),
        ]);

        $rules = [
            'judul' => ['required', 'string', 'max:200'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
        $messages = ['judul.required' => 'Judul wajib diisi.'];

        if (! $this->useBody($jenis)) {
            $rules['tautan'] = ['nullable', 'string', 'max:255', function ($attr, $value, $fail) {
                if ($value && MenuLink::isUnsafe($value)) {
                    $fail('Tautan tidak diizinkan.');
                }
            }];
        }
        if ($this->useFile($jenis)) {
            $ext = $this->allowedExtensions($jenis);
            $rules['berkas'] = ['nullable', 'file', 'mimes:'.implode(',', $ext), 'max:'.config('diskominfo.max_file_kb')];
            $messages['berkas.mimes'] = 'Tipe berkas harus: '.implode(', ', $ext).'.';
            $messages['berkas.max'] = 'Ukuran berkas maksimal 10 MB.';
        }

        $v = $request->validate($rules, $messages);

        return [
            'judul' => $v['judul'],
            'slug' => $jenis === 'halaman' ? \App\Support\Media::slug($v['judul']) : null,
            'isi' => $this->useBody($jenis) ? HtmlSanitizer::clean($request->input('content')) : null,
            'tautan' => $this->useBody($jenis) ? null : (($v['tautan'] ?? '') ?: null),
            'status' => $v['status'],
        ];
    }
}
