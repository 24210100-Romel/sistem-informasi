@props(['href', 'icon', 'active' => false, 'target' => null])
<a class="pn-link{{ $active ? ' active' : '' }}" href="{{ $href }}" @if($target) target="{{ $target }}" rel="noopener" @endif><i class="bi {{ $icon }}"></i><span>{{ $slot }}</span></a>
