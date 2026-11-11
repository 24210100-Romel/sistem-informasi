<aside class="side-stack" aria-label="Informasi tambahan">
  <div class="panel">
    <div class="panel-title">Layanan Informasi</div>
    <div class="service-list">
      @foreach($sideServices as $it)
        @php $ic = preg_match('/^bi-[a-z0-9-]+$/', (string) $it->ikon) ? $it->ikon : 'bi-file-earmark-text'; @endphp
        <a href="{{ \App\Support\MenuLink::href($it->url) }}"><i class="bi {{ $ic }}"></i> {{ $it->title }}</a>
      @endforeach
      @if($sideServices->isEmpty())<div class="empty-state">Belum ada layanan.</div>@endif
    </div>
  </div>

  <div class="panel">
    <div class="panel-title">Berita Terbaru</div>
    @foreach($sideRecent as $a)
      @php $au = route('berita.show', $a->slug); @endphp
      <article class="mini-news">
        <a class="mini-thumb" href="{{ $au }}" tabindex="-1" aria-hidden="true">@if($a->image_url)<img src="{{ $a->image_url }}" alt="" loading="lazy">@else<i class="bi bi-image"></i>@endif</a>
        <div>
          <h3><a href="{{ $au }}">{{ $a->title }}</a></h3>
          <small><i class="bi bi-calendar3"></i> {{ $a->created_at->format('d-m-Y') }}</small>
        </div>
      </article>
    @endforeach
    @if($sideRecent->isEmpty())<div class="empty-state">Belum ada berita.</div>@endif
    <a class="panel-more" href="{{ route('berita.index') }}">Lihat semua berita <i class="bi bi-chevron-right"></i></a>
  </div>
</aside>
