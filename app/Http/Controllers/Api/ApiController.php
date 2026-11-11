<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function ok(array $payload = [], int $status = 200): JsonResponse
    {
        return response()->json(['success' => true] + $payload, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function fail(string $message, int $status = 400): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], $status, [], JSON_UNESCAPED_UNICODE);
    }

    protected function article(Article $a): array
    {
        return [
            'id' => $a->id,
            'title' => $a->title,
            'slug' => $a->slug,
            'content' => $a->content,
            'image' => $a->image,
            'image_url' => $a->image_url,
            'category_id' => $a->category_id,
            'category_name' => $a->category?->nama,
            'author_id' => $a->author_id,
            'author_name' => $a->author?->nama,
            'status' => $a->status,
            'is_featured' => (bool) $a->is_featured,
            'created_at' => $a->created_at?->toIso8601String(),
            'updated_at' => $a->updated_at?->toIso8601String(),
        ];
    }

    protected function user(User $u): array
    {
        return [
            'id' => $u->id,
            'nama' => $u->nama,
            'username' => $u->username,
            'role' => $u->role,
            'created_at' => $u->created_at?->toIso8601String(),
        ];
    }
}
