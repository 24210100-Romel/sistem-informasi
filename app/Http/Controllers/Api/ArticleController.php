<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiAuthenticate;
use App\Models\Article;
use App\Support\HtmlSanitizer;
use App\Support\MenuLink;
use App\Support\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ArticleController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Article::with(['category', 'author']);

        // Hanya staf (token admin/operator) yang boleh melihat berita non-terbit.
        $staff = ApiAuthenticate::userFrom($request);
        $status = (string) $request->query('status', 'published');
        if ($status === 'published' || ! $staff) {
            $query->published();
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }
        if ($request->filled('slug')) {
            $article = $query->where('slug', $request->query('slug'))->first();

            return $article ? $this->ok(['data' => $this->article($article)]) : $this->fail('Berita tidak ditemukan.', 404);
        }

        $limit = min(max((int) $request->query('limit', 10), 1), 100);
        $offset = max((int) $request->query('offset', 0), 0);
        $total = (clone $query)->count();

        $items = $query->orderByDesc('is_featured')->orderByDesc('created_at')->orderByDesc('id')
            ->offset($offset)->limit($limit)->get()
            ->map(fn (Article $a) => $this->article($a))->all();

        return $this->ok([
            'data' => $items,
            'pagination' => ['limit' => $limit, 'offset' => $offset, 'count' => count($items), 'total' => $total],
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $article = Article::with(['category', 'author'])->find($id);
        $staff = ApiAuthenticate::userFrom($request);

        if (! $article || ($article->status !== 'published' && ! $staff)) {
            return $this->fail('Berita tidak ditemukan.', 404);
        }

        return $this->ok(['data' => $this->article($article)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;

        $article = DB::transaction(function () use ($data) {
            $article = Article::create($data);
            if ($data['is_featured']) {
                Article::setFeatured($article->id);
            }

            return $article;
        });

        return $this->ok(['message' => 'Berita berhasil ditambahkan.', 'id' => $article->id], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (! $article) {
            return $this->fail('Berita tidak ditemukan.', 404);
        }

        $data = $this->validated($request, $article);

        DB::transaction(function () use ($data, $article) {
            $article->update($data);
            if ($data['is_featured']) {
                Article::setFeatured($article->id);
            }
        });

        return $this->ok(['message' => 'Berita berhasil diperbarui.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $article = Article::find($id);
        if (! $article) {
            return $this->fail('Berita tidak ditemukan.', 404);
        }
        $article->delete();

        return $this->ok(['message' => 'Berita berhasil dihapus.']);
    }

    /** Pada PUT/PATCH, field yang tidak dikirim mempertahankan nilai lama. */
    private function validated(Request $request, ?Article $article = null): array
    {
        $in = $request->all();
        $title = trim((string) ($in['title'] ?? $article?->title ?? ''));
        $slugIn = trim((string) ($in['slug'] ?? ''));
        $slug = $slugIn !== '' ? Media::slug($slugIn) : ($article ? $article->slug : Media::slug($title));

        $request->merge([
            'title' => $title,
            'slug' => $slug,
            'content' => array_key_exists('content', $in) ? HtmlSanitizer::clean((string) $in['content']) : ($article?->content ?? ''),
            'status' => $in['status'] ?? $article?->status ?? 'draft',
        ]);

        $v = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', Rule::unique('articles', 'slug')->ignore($article)],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['nullable', 'string', 'max:255', function ($a, $value, $fail) {
                if ($value && MenuLink::isUnsafe($value)) {
                    $fail('image tidak valid.');
                }
            }],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'title dan content wajib diisi.',
            'content.required' => 'title dan content wajib diisi.',
            'slug.unique' => 'slug sudah digunakan.',
        ]);

        return [
            'title' => $v['title'],
            'slug' => $v['slug'],
            'content' => $v['content'],
            'status' => $v['status'],
            'category_id' => array_key_exists('category_id', $in) ? (($v['category_id'] ?? null) ?: null) : $article?->category_id,
            'image' => array_key_exists('image', $in) ? ($v['image'] ?? null) : $article?->image,
            'is_featured' => array_key_exists('is_featured', $in) ? $request->boolean('is_featured') : (bool) $article?->is_featured,
        ];
    }
}
