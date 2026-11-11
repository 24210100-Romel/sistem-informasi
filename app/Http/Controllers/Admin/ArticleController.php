<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Support\HtmlSanitizer;
use App\Support\Media;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author'])
            ->orderByDesc('is_featured')->orderByDesc('created_at')->orderByDesc('id')
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.form', [
            'article' => new Article(['status' => 'published']),
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;

        DB::transaction(function () use ($request, $data) {
            if ($request->hasFile('image')) {
                $data['image'] = Uploads::store($request->file('image'));
            }
            $article = Article::create($data);
            if ($data['is_featured']) {
                Article::setFeatured($article->id);
            }
        });

        return redirect()->route('admin.artikel.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', [
            'article' => $article,
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request, $article);

        DB::transaction(function () use ($request, $data, $article) {
            if ($request->hasFile('image')) {
                Uploads::delete($article->image);
                $data['image'] = Uploads::store($request->file('image'));
            }
            $article->update($data);
            if ($data['is_featured']) {
                Article::setFeatured($article->id);
            }
        });

        return redirect()->route('admin.artikel.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        Uploads::delete($article->image);
        $article->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Berita berhasil dihapus.');
    }

    /** Validasi + normalisasi (slug otomatis, HTML dibersihkan). */
    private function validated(Request $request, ?Article $article = null): array
    {
        Uploads::prepare($request, 'image', 'Gambar');

        $title = trim((string) $request->input('title'));
        $slug = trim((string) $request->input('slug'));
        $request->merge([
            // Opsi "Tanpa kategori" mengirim 0/kosong -> dianggap tidak ada kategori.
            'category_id' => $request->integer('category_id') ?: null,
            'content' => HtmlSanitizer::clean($request->input('content')),
            'slug' => $slug !== '' ? Media::slug($slug) : Media::slug($title),
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', Rule::unique('articles', 'slug')->ignore($article)],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.config('diskominfo.max_image_kb')],
        ], [
            'slug.unique' => 'Slug sudah digunakan berita lain. Ubah slug agar unik.',
            'slug.required' => 'Slug tidak dapat dibuat dari judul ini. Isi slug secara manual.',
            'content.required' => 'Isi berita wajib diisi.',
            'image.image' => 'Gambar harus JPG, PNG, atau WEBP.',
            'image.mimes' => 'Gambar harus JPG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        unset($data['image']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['category_id'] = ($data['category_id'] ?? null) ?: null;

        return $data;
    }
}
