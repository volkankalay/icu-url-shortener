@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">
    @can('update', $url)
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endcan
@endpush

@section('dashboardcontent')
    @php($shortUrl = rtrim($settings->site, '/') . '/' . $url->short)
    @php($editableOwner = null)
    <header class="dashboard-panel link-detail-header">
        <a class="link-detail-back" href="{{ route('url.list') }}">← Bağlantılarım</a>
        <div class="dashboard-heading">
            <div>
                <h1>/{{ $url->short }}</h1>
                <p class="dashboard-destination"><span>Hedef:</span> {{ $url->url }}</p>
                @if ($url->owner_id !== auth()->id())
                    @can('viewAny', \App\Models\Link::class)
                        @php($owner = $url->owner)
                        @php($editableOwner = auth()->user()->isAdmin() && $owner && !$owner->trashed() ? $owner : null)
                        <div class="link-detail-actions align-items-center mt-2">
                            <p class="dashboard-destination m-0"><span>Bağlantı sahibi:</span>
                                {{ $owner?->full_name ?? '—' }}@if ($owner) ({{ '@' . $owner->username }})@endif
                            </p>
                            @if ($editableOwner)
                                <button type="button" class="dashboard-button" data-toggle="modal" data-target="#user-type-modal">
                                    <i class="fas fa-user-edit" aria-hidden="true"></i> Kullanıcıyı düzenle
                                </button>
                            @endif
                        </div>
                    @endcan
                @endif
            </div>
            <div class="link-detail-actions">
                @can('update', $url)
                    <button type="button" class="dashboard-button" data-toggle="modal" data-target="#updateModal"><i class="fas fa-pen" aria-hidden="true"></i> Düzenle</button>
                @endcan
                <a class="dashboard-button dashboard-primary" href="{{ route('direct', $url->short) }}" target="_blank" rel="noopener">Bağlantıyı aç <span aria-hidden="true">↗</span></a>
            </div>
        </div>
        <div class="link-detail-share">
            <input class="dashboard-input" aria-label="Kısa bağlantı" readonly value="{{ $shortUrl }}">
            <button type="button" class="dashboard-button" data-copy-value="{{ $shortUrl }}" data-copy-status="copy-status"><i class="fas fa-copy" aria-hidden="true"></i> Kopyala</button>
        </div>
        <span id="copy-status" class="dashboard-copy-status dashboard-muted" role="status"></span>
    </header>

    <div class="dashboard-stats link-detail-stats">
        <x-dashboard.stat label="Toplam yönlendirme" :value="$url->total_clicks" icon="external-link-alt" />
        <x-dashboard.stat label="Toplam ziyaret kaydı" :value="$visitors->count()" icon="chart-bar" />
        <x-dashboard.stat label="Toplam tekil IP" :value="$periods['Tüm zamanlar']['uniqueIps']" icon="users" />
    </div>

    <div class="dashboard-overview link-detail-overview">
        <section class="dashboard-panel link-detail-chart">
            <h2>Son 7 gün</h2>
            <p class="dashboard-muted">Günlük tekil IP sayısı</p>
            <x-dashboard.activity-chart :activity="$activity" label="Son 7 günün tekil IP sayıları" />
        </section>

        <section class="dashboard-panel link-detail-summary">
            <h2>Ziyaret özeti</h2>
            <div class="table-responsive">
                <table class="table dashboard-table">
                    <thead><tr><th scope="col">Dönem</th><th scope="col">Ziyaret</th><th scope="col">Tekil IP</th></tr></thead>
                    <tbody>
                        @foreach ($periods as $label => $totals)
                            <tr><th scope="row">{{ $label }}</th><td>{{ $totals['visits'] }}</td><td>{{ $totals['uniqueIps'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <dl class="dashboard-facts link-detail-facts">
                <div><dt>En sık kullanılan tarayıcı</dt><dd>{{ $favBrowser }}</dd></div>
                <div><dt>En sık kullanılan işletim sistemi</dt><dd>{{ $favOS }}</dd></div>
            </dl>
        </section>
    </div>

    <section class="dashboard-panel link-detail-history">
        <h2>Ziyaret geçmişi</h2>
        @if ($visitors->isEmpty())
            <p class="dashboard-muted">Bu bağlantı henüz görüntülenmedi.</p>
        @else
            <div class="table-responsive">
                <table class="table dashboard-table" id="visits-table">
                    <thead><tr><th scope="col">IP adresi</th><th scope="col">Tarayıcı</th><th scope="col">İşletim sistemi</th><th scope="col">Cihaz</th><th scope="col">Kaynak</th><th scope="col">Dil</th><th scope="col">Tarih</th></tr></thead>
                    <tbody>
                        @foreach ($visitors as $visit)
                            <tr>
                                <td>{{ $visit->options?->ip() ?? '—' }}</td>
                                <td>{{ trim(($visit->options?->browser ?? '—').' '.($visit->options?->browserVersion ?? '')) }}</td>
                                <td>{{ trim(($visit->options?->os ?? '—').' '.($visit->options?->osVersion ?? '')) }}</td>
                                <td>{{ $visit->options?->device ?? '—' }}</td>
                                <td>{{ $visit->options?->referrerHost ?? '—' }}</td>
                                <td>{{ $visit->options?->language ?? '—' }}</td>
                                <td data-order="{{ $visit->created_at?->timestamp }}">{{ $visit->getDate() ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
    @can('update', $url)
        @include('modals.LinkExtensionChange', ['shortCode' => $url->short])
    @endcan
    @if ($editableOwner)
        @include('modals.userBeMod', ['targetUser' => $editableOwner])
    @endif
@endsection

@section('js')
    <script src="{{ asset('js/dashboard-copy.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script>
        $('#visits-table').DataTable({
            order: [[6, 'desc']],
            language: { search: 'Ara:', lengthMenu: '_MENU_ ziyaret göster', info: '_TOTAL_ ziyaretten _START_–_END_', infoEmpty: 'Ziyaret yok', zeroRecords: 'Eşleşen ziyaret yok', paginate: { previous: 'Önceki', next: 'Sonraki' } }
        });

        @if (auth()->user()->can('update', $url) && $errors->hasAny(['shortold', 'shortnew']))
            $('#updateModal').modal('show');
        @endif
    </script>
@endsection
