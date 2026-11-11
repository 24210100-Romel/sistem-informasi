@foreach($by[$pid] ?? [] as $m)
  @php
    $has = ! empty($by[(int) $m->id]);
    $cls = trim(($has ? 'has-sub ' : '').(\App\Support\MenuLink::branchActive($by, $m) ? 'active' : ''));
  @endphp
  <li @if($cls) class="{{ $cls }}" @endif><a href="{{ \App\Support\MenuLink::href($m->url) }}" @if($has) aria-haspopup="true" @endif>{{ $m->title }}</a>
    @if($has)
      <ul class="nav-sub">@include('partials.nav-items', ['by' => $by, 'pid' => (int) $m->id])</ul>
    @endif
  </li>
@endforeach
