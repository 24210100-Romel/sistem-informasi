<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeaturedController extends Controller
{
    public function edit()
    {
        return view('admin.featured', [
            'featured' => Article::with('category')->where('is_featured', true)->first(),
            'published' => Article::published()->orderByDesc('created_at')->get(['id', 'title', 'is_featured']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $id = (int) $request->input('article_id', 0);

        if ($id && ! Article::published()->whereKey($id)->exists()) {
            return back()->withErrors(['article_id' => 'Berita harus berstatus Terbit agar dapat dijadikan Berita Utama.']);
        }

        Article::setFeatured($id ?: null);

        return redirect()->route('admin.berita-utama')
            ->with('success', $id ? 'Berita Utama berhasil diatur.' : 'Berita Utama dikosongkan.');
    }
}
