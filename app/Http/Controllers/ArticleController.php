<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latestPublished()->with('category')->paginate(12);

        return view('public.articles', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = Article::published()->with(['category', 'author'])->where('slug', $slug)->firstOrFail();

        return view('public.article', compact('article'));
    }
}
