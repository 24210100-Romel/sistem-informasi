@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="crumb">Referensi &rsaquo; <b>Pengaturan</b></div>
<div class="card shadow-sm" style="max-width:720px"><div class="card-body p-4 pn-form">
  <form method="post" action="{{ route('admin.pengaturan.update') }}">@csrf
    @foreach($fields as $k => $lbl)
      <label>{{ $lbl }}</label>
      @if($k === 'ai_aktif')
        <select class="form-select mb-1" name="ai_aktif">
          <option value="1" @selected(old('ai_aktif', $current['ai_aktif'] ?? '1') !== '0')>Aktif</option>
          <option value="0" @selected(old('ai_aktif', $current['ai_aktif'] ?? '1') === '0')>Nonaktif</option>
        </select>
        <div class="small text-muted mb-3">Mode saat ini: <b>{{ $aiMode }}</b>. Kunci API diisi di berkas <code>.env</code>, bukan di sini.</div>
      @elseif(in_array($k, $textarea, true))<textarea class="form-control mb-3" name="{{ $k }}" rows="2">{{ old($k, $current[$k] ?? '') }}</textarea>
      @else<input class="form-control mb-3" name="{{ $k }}" value="{{ old($k, $current[$k] ?? '') }}">@endif
    @endforeach
    <button class="btn btn-primary">Simpan Pengaturan</button>
  </form>
</div></div>
@endsection
