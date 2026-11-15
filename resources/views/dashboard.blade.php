@extends('layouts.themed')

@section('body-class', 'landing dashboard-layout')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-shell">
        <nav class="dashboard-navigation" aria-label="Panel menüsü">
            <div class="dashboard-nav-group">
                @foreach ([
                    ['dashboard', 'dashboard', 'th-large', 'Genel bakış'],
                    ['url.list', ['url.list', 'url.show'], 'link', 'Bağlantılarım'],
                    ['profile.index', 'profile.*', 'user-circle', 'Profilim'],
                ] as [$route, $active, $icon, $label])
                    <x-dashboard.nav-link :route="$route" :active="$active" :icon="$icon">{{ $label }}</x-dashboard.nav-link>
                @endforeach
            </div>
            @can('viewAny', \App\Models\Link::class)
                <div class="dashboard-nav-group">
                    <p>Yönetim</p>
                    @if (auth()->user()->isAdmin())
                        <x-dashboard.nav-link route="admin.settings" active="admin.settings*" icon="cog">Site ayarları</x-dashboard.nav-link>
                        <x-dashboard.nav-link route="admin.users" active="admin.users*" icon="users">Kullanıcılar</x-dashboard.nav-link>
                    @endif
                    <x-dashboard.nav-link route="admin.links" active="admin.links*" icon="list">Tüm bağlantılar</x-dashboard.nav-link>
                </div>
            @endcan
        </nav>
        <main class="dashboard-main">
            @yield('dashboardcontent')
        </main>
    </div>
@endsection
