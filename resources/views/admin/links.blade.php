@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">
@endpush

@section('dashboardcontent')
    <header class="dashboard-heading">
        <div><h1>Tüm bağlantılar</h1><p>{{ $links->count() }} aktif bağlantı</p></div>
    </header>

    @if ($errors->any())
        <p class="dashboard-panel" role="alert">{{ $errors->first() }}</p>
    @endif
    <span id="admin-copy-status" role="status" class="dashboard-muted" hidden></span>

    <x-dashboard.admin-table label="Tüm bağlantılar" :empty="$links->isEmpty() ? 'Gösterilecek aktif bağlantı yok.' : null"
        :sort-options="['0:asc' => 'Kısa kod (A–Z)', '0:desc' => 'Kısa kod (Z–A)', '1:asc' => 'Hedef adres (A–Z)', '1:desc' => 'Hedef adres (Z–A)', '2:asc' => 'Sahip (A–Z)', '2:desc' => 'Sahip (Z–A)', '3:desc' => 'En çok tekil ziyaret', '3:asc' => 'En az tekil ziyaret']">
        <x-slot:head>
            <th scope="col" role="columnheader">Kısa bağlantı</th>
            <th scope="col" role="columnheader">Hedef adres</th>
            <th scope="col" role="columnheader">Sahip</th>
            <th scope="col" role="columnheader">Tekil ziyaret</th>
            <th scope="col" role="columnheader" class="links-actions">İşlemler</th>
        </x-slot:head>
        @foreach ($links as $link)
            <tr role="row">
                <td role="cell" class="links-short" data-order="{{ $link->short }}">
                    <a href="{{ route('direct', $link->short) }}" target="_blank" rel="noopener">/{{ $link->short }} <span aria-hidden="true">↗</span></a>
                    <button type="button" class="dashboard-button admin-copy" aria-label="Kısa adresi kopyala"
                            data-copy-value="{{ rtrim($settings->site, '/') }}/{{ $link->short }}" data-copy-status="admin-copy-status">
                        <i class="fas fa-copy" aria-hidden="true"></i>
                    </button>
                </td>
                <td role="cell" class="links-target">
                    <span class="links-cell-label" aria-hidden="true">Hedef adres</span>
                    <a href="{{ $link->url }}" target="_blank" rel="noopener" title="{{ $link->url }}">{{ $link->url }}</a>
                </td>
                <td role="cell"><span class="links-cell-label" aria-hidden="true">Sahip</span>{{ $link->owner?->username ?? '—' }}</td>
                <td role="cell" class="links-views" data-order="{{ $link->visitorCount() }}">
                    <span class="links-cell-label" aria-hidden="true">Tekil ziyaret</span>
                    {{ $link->visitorCount() }} <small class="admin-secondary">{{ $link->total_clicks }} yönlendirme</small>
                </td>
                <td role="cell" class="links-actions">
                    <div class="admin-table-actions">
                        <a class="dashboard-button" href="{{ route('url.show', $link->short) }}">Detaylar <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        @if (auth()->user()->isAdmin())
                            <button type="button" class="dashboard-button admin-danger" data-toggle="modal" data-target="#delete-link-modal"
                                data-disable-link="{{ $link->id }}" data-short-code="{{ $link->short }}">Devre dışı bırak</button>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-dashboard.admin-table>

    @if (auth()->user()->isAdmin())
        @include('modals.LinkDelete')
    @endif
@endsection

@section('js')
    @if ($links->isNotEmpty())
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/admin-table.js') }}"></script>
        <script src="{{ asset('js/dashboard-copy.js') }}"></script>
        <script>
            $('#delete-link-modal').on('show.bs.modal', (event) => {
                const link = event.relatedTarget.dataset;
                $('#delete-link-id').val(link.disableLink);
                $('#delete-link-code').text(link.shortCode);
            });
        </script>
    @endif
@endsection
