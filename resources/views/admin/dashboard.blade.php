@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h2 class="h4 fw-bold mb-0">Dashboard</h2>
<p class="text-muted mb-3">Selamat datang, {{ explode(' ', trim($userName))[0] ?: 'Admin' }}!</p>
<div class="ad-stats">
  <div class="ad-stat s1"><i class="bi bi-file-earmark-text-fill"></i><div><small>Total Artikel</small><b>{{ $articleCount }}</b><em>↑ 12%</em></div></div>
  <div class="ad-stat s2"><i class="bi bi-people-fill"></i><div><small>Total Pengguna</small><b>{{ $userCount }}</b><em>↑ 8%</em></div></div>
  <div class="ad-stat s3"><i class="bi bi-info-circle-fill"></i><div><small>Total Layanan</small><b>{{ $serviceCount }}</b><em>↑ 0%</em></div></div>
</div>
<div class="ad-grid">
  <section class="ad-card"><h3>Statistik Publikasi Artikel <span class="float-end small fw-normal text-muted">12 Bulan</span></h3>
    <div style="height:260px"><canvas id="newsChart"></canvas></div></section>
  <section class="ad-card"><h3>Aktivitas Terbaru</h3>
    @foreach($activity as $a)
      <div class="ad-act"><i class="bi {{ $a['icon'] }}"></i><div><p>{{ $a['text'] }}</p><small>{{ $a['when'] }}</small></div></div>
    @endforeach
  </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('newsChart'),{type:'line',data:{labels:@json($days),datasets:[{data:@json($counts),borderColor:'#1b5fd8',backgroundColor:'rgba(27,95,216,.12)',fill:true,tension:.35,pointRadius:4,pointBackgroundColor:'#1b5fd8',borderWidth:2.5}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{precision:0},grid:{color:'#edf0f4'}}}}});
</script>
@endpush
