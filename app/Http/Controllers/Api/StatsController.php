<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatsController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->ok(['data' => [
            'articles' => [
                'total' => Article::count(),
                'published' => Article::published()->count(),
                'draft' => Article::where('status', 'draft')->count(),
                'featured' => Article::where('is_featured', true)->count(),
            ],
            'categories' => Category::count(),
            'users' => User::count(),
        ]]);
    }

    public function root(): JsonResponse
    {
        return $this->ok([
            'name' => 'DISKOMINFO Manado API',
            'version' => '2.0',
            'endpoints' => [
                'POST /api/login', 'GET /api/me',
                'GET /api/articles', 'GET /api/articles/{id}', 'POST|PUT|PATCH|DELETE /api/articles[/{id}]',
                'GET /api/categories', 'POST|PUT|PATCH|DELETE /api/categories[/{id}]',
                'GET|POST|PUT|PATCH|DELETE /api/users[/{id}]',
                'GET|PUT|PATCH /api/featured', 'GET /api/stats',
            ],
        ]);
    }
}
