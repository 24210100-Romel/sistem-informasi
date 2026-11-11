<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeaturedController extends ApiController
{
    public function show(): JsonResponse
    {
        $a = Article::published()->with(['category', 'author'])->where('is_featured', true)
            ->orderByDesc('created_at')->first();

        return $this->ok(['data' => $a ? $this->article($a) : null]);
    }

    public function update(Request $request): JsonResponse
    {
        $id = (int) $request->input('article_id', 0);
        if ($id <= 0) {
            return $this->fail('article_id wajib diisi.', 422);
        }
        if (! Article::published()->whereKey($id)->exists()) {
            return $this->fail('Berita tidak ditemukan atau belum berstatus terbit.', 422);
        }

        Article::setFeatured($id);

        return $this->ok(['message' => 'Berita Utama berhasil diatur.']);
    }
}
