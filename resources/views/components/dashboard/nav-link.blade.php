@props(['route', 'active', 'icon'])

@php($current = request()->routeIs(...(array) $active))
<a href="{{ route($route) }}" @class(['dashboard-nav-link', 'is-active' => $current])
   @if ($current) aria-current="page" @endif>
    <i class="fas fa-{{ $icon }}" aria-hidden="true"></i>
    {{ $slot }}
</a>
