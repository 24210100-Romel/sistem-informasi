@extends('layouts.public')
@section('title', 'Berita - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner kicker="Informasi Terkini" title="Berita" :crumbs="[['Berita', null]]" />
  <div class="container inner-container">
    <div class="panel">
      <div class="panel-title">Semua Berita</div>
      <div class="panel-body">
        <div class="news-grid">
          @forelse($articles as $a)
            @php $u = route('berita.show', $a->slug); @endphp
            <article class="news-card">
              <a class="news-image" href="{{ $u }}" tabindex="-1" aria-hidden="true">@if($a->image_url)<img src="{{ $a->image_url }}" alt="" loading="lazy">@else<i class="bi bi-newspaper"></i>@endif</a>
              <div class="news-body">
                <span class="badge-soft">{{ $a->category->nama ?? 'Informasi' }}</span>
                <h2 class="news-h"><a href="{{ $u }}">{{ $a->title }}</a></h2>
                <p class="news-p">{{ $a->excerpt(120) }}</p>
                <small class="news-date"><i class="bi bi-calendar3"></i> {{ $a->created_at->format('d-m-Y') }}</small>
              </div>
            </article>
          @empty
            <div class="empty-state grid-full">Belum ada berita.</div>
          @endforelse
        </div>
        @if($articles->hasPages())<div class="pager">{{ $articles->links() }}</div>@endif
      </div>
    </div>
  </div>
</main>
@endsection
