@foreach(['success', 'danger'] as $type)
  @if(session($type))
    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">{{ session($type) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif
@endforeach
@if($errors->any())
  <div class="alert alert-danger" role="alert">
    @if($errors->count() === 1){{ $errors->first() }}@else<ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>@endif
  </div>
@endif
