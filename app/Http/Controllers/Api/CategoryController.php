<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends ApiController
{
    public function index(): JsonResponse
    {
        $rows = Category::withCount('articles')->orderBy('nama')->get()->map(fn (Category $c) => [
            'id' => $c->id,
            'nama' => $c->nama,
            'created_at' => $c->created_at?->toIso8601String(),
            'article_count' => $c->articles_count,
        ]);

        return $this->ok(['data' => $rows]);
    }

    public function store(Request $request): JsonResponse
    {
        $category = Category::create($this->validated($request));

        return $this->ok(['message' => 'Kategori berhasil ditambahkan.', 'id' => $category->id], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);
        if (! $category) {
            return $this->fail('Kategori tidak ditemukan.', 404);
        }
        $category->update($this->validated($request, $category));

        return $this->ok(['message' => 'Kategori berhasil diperbarui.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::find($id);
        if (! $category) {
            return $this->fail('Kategori tidak ditemukan.', 404);
        }
        $category->delete();

        return $this->ok(['message' => 'Kategori berhasil dihapus.']);
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $request->merge(['nama' => trim((string) $request->input('nama'))]);

        return $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('categories', 'nama')->ignore($category)],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah digunakan.',
        ]);
    }
}
