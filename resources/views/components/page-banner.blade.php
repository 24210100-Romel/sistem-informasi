@props(['title', 'crumbs' => [], 'kicker' => null])
<section class="page-hero" aria-label="{{ $title }}">
  @if(!empty($bannerBg))<img class="page-hero-bg" src="{{ $bannerBg }}" alt="" aria-hidden="true" fetchpriority="high">@endif
  <div class="hero-shade"></div>
  <div class="page-hero-inner">
    @if($kicker)<p class="hero-kecil">{{ $kicker }}</p>@endif
    <h1 class="page-hero-title">{{ $title }}</h1>
    <nav aria-label="Breadcrumb"><ol class="crumbs"><li><a href="{{ route('home') }}">Beranda</a></li>
      @foreach($crumbs as $i => [$label, $url])
        @php $last = $i === array_key_last($crumbs); @endphp
        <li @if($last) aria-current="page" @endif>@if($url && ! $last)<a href="{{ $url }}">{{ $label }}</a>@else{{ $label }}@endif</li>
      @endforeach
    </ol></nav>
  </div>
</section>
