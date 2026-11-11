@extends('layouts.public')
@section('title', $article->title.' - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner kicker="Berita" :title="\Illuminate\Support\Str::limit($article->title, 90)" :crumbs="[['Berita', route('berita.index')], [\Illuminate\Support\Str::limit($article->title, 50), null]]" />
  <div class="container inner-container">
    <div class="top-grid">
      <article class="panel">
        <div class="panel-title">Detail Berita</div>
        <div class="panel-body">
          <h2 class="article-title">{{ $article->title }}</h2>
          <div class="article-meta">
            <span><i class="bi bi-calendar3"></i> {{ $article->created_at->translatedFormat('d M Y') }}</span>
            @if($article->category)<span><i class="bi bi-tag"></i> {{ $article->category->nama }}</span>@endif
            @if($article->author)<span><i class="bi bi-person"></i> {{ $article->author->nama }}</span>@endif
          </div>
          @if($article->image_url)<img class="article-hero" src="{{ $article->image_url }}" alt="{{ $article->title }}">@endif
          <div class="article-content">{!! $article->content !!}</div>
          <a class="back-link" href="{{ route('berita.index') }}"><i class="bi bi-arrow-left"></i> Kembali ke daftar berita</a>
        </div>
      </article>
      @include('partials.sidebar', ['exceptId' => $article->id])
    </div>
  </div>
</main>
@endsection
