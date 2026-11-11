<ul class="tree-list">
@foreach($by[$pid] ?? [] as $r)
  @php $id = (int) $r->id; @endphp
  <li data-id="{{ $id }}"><div class="tree-row lv{{ min($lv, 1) }}">
    <div class="t" style="--lv:{{ $lv }}"><i class="bi {{ $lv ? 'bi-arrow-return-right' : 'bi-grip-vertical' }} ti"></i>@if(!empty($r->ikon) && preg_match('/^bi-[a-z0-9-]+$/', $r->ikon))<i class="bi {{ $r->ikon }} ico"></i>@endif<span class="{{ $r->aktif ? '' : 'off' }}">{{ $r->title }}</span>@unless($r->aktif)<em class="tag">Nonaktif</em>@endunless</div>
    <div class="u" title="{{ $r->url }}">{{ $r->url }}</div>
    <div class="act">
      <a class="ic-btn" href="{{ route('admin.menu.index', [$jenis, 'edit' => $id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
      <form method="post" action="{{ route('admin.menu.destroy', [$jenis, $r]) }}" onsubmit="return confirm('Hapus menu ini beserta submenunya?')">@csrf @method('DELETE')<button class="ic-btn" title="Hapus"><i class="bi bi-trash"></i></button></form>
    </div>
  </div>
  @include('admin.partials.menu-tree', ['by' => $by, 'pid' => $id, 'lv' => $lv + 1, 'jenis' => $jenis])
  </li>
@endforeach
</ul>
