@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('dashboardcontent')
    <header class="dashboard-heading">
        <div>
            <h1>Genel bakış</h1>
            <p>Hoş geldin, {{ auth()->user()->full_name }}.</p>
        </div>
    </header>

    <section id="baglanti-olustur" class="dashboard-panel dashboard-create-panel" aria-labelledby="shortener-title">
        <h2 id="shortener-title">Bağlantı oluştur</h2>
        <form class="dashboard-form dashboard-shortener" method="post" action="{{ route('url.create.post') }}">
            @csrf
            <x-auth.field name="url" :label="__('homepage.linklabel')" type="url" inputmode="url" placeholder="https://example.com/…" maxlength="191" />
            <button class="dashboard-button dashboard-primary" type="submit">@lang('homepage.shortbtn') <span aria-hidden="true">↗</span></button>
        </form>
        @if ($short = session('short_link'))
            @php($shortUrl = rtrim($settings->site, '/').'/'.$short)
            <div class="dashboard-result">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <div>
                    <strong>Kısa bağlantın hazır</strong>
                    <div class="dashboard-result-actions">
                        <a href="{{ route('direct', $short) }}" target="_blank" rel="noopener">{{ $shortUrl }} <span aria-hidden="true">↗</span></a>
                        <button type="button" class="dashboard-button" data-copy-value="{{ $shortUrl }}" data-copy-status="short-link-copy-status">
                            <i class="fas fa-copy" aria-hidden="true"></i> Kopyala
                        </button>
                    </div>
                    <span id="short-link-copy-status" class="dashboard-copy-status" role="status"></span>
                </div>
            </div>
        @endif
    </section>

    <div class="dashboard-stats">
        <x-dashboard.stat label="Bağlantılarım" :value="$linkCount" icon="link" />
        <x-dashboard.stat label="Toplam görüntülenme" :value="$totalClicks" icon="eye" />
        <x-dashboard.stat label="Bugünkü ziyaret kayıtları" :value="$todayVisits" icon="clock" />
        <x-dashboard.stat label="Bu ayki ziyaret kayıtları" :value="$monthVisits" icon="calendar-alt" />
    </div>

    <div class="dashboard-overview">
        <section class="dashboard-panel">
            <h2>Son 7 gün</h2>
            <p class="dashboard-muted">Günlük ziyaret kayıtları</p>
            <x-dashboard.activity-chart :activity="$activity" label="Son 7 günün ziyaret kayıtları" />
        </section>
        <section class="dashboard-panel">
            <h2>Son giriş</h2>
            @if ($lastLogin)
                <dl class="dashboard-facts">
                    <div><dt>Tarih</dt><dd>{{ $lastLogin->getDate() ?? '—' }}</dd></div>
                    <div><dt>Tarayıcı</dt><dd>{{ $lastLogin->browser ?? '—' }}</dd></div>
                    <div><dt>İşletim sistemi</dt><dd>{{ $lastLogin->os ?? '—' }}</dd></div>
                </dl>
            @else
                <p class="dashboard-muted">Henüz giriş kaydı yok.</p>
            @endif
        </section>
        <section class="dashboard-panel dashboard-recent-panel">
            <div class="dashboard-panel-heading">
                <h2>Son bağlantılar</h2>
                @if ($recentLinks->isNotEmpty())
                    <a href="{{ route('url.list') }}">Tümünü gör <span aria-hidden="true">→</span></a>
                @endif
            </div>
            @forelse ($recentLinks as $link)
                <a class="dashboard-recent-link" href="{{ route('url.show', $link->short) }}">
                    <span><strong>/{{ $link->short }}</strong><small>{{ $link->url }}</small></span>
                    <span>{{ $link->total_clicks }} görüntülenme <span aria-hidden="true">→</span></span>
                </a>
            @empty
                <p class="dashboard-muted">Henüz kısa bağlantın yok.</p>
                <a class="dashboard-button" href="#baglanti-olustur">İlk bağlantını oluştur</a>
            @endforelse
        </section>
    </div>
@endsection

@section('js')
    @if (session()->has('short_link'))
        <script src="{{ asset('js/dashboard-copy.js') }}"></script>
    @endif
@endsection
