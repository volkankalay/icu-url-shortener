@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">
@endpush

@section('dashboardcontent')
    <header class="dashboard-heading">
        <div><h1>Bağlantılarım</h1><p>Tekil ziyaretler, her bağlantı için IP başına günde bir kez sayılır.</p></div>
        <a class="dashboard-button dashboard-primary" href="{{ route('dashboard') }}#baglanti-olustur"><i class="fas fa-plus" aria-hidden="true"></i> Bağlantı oluştur</a>
    </header>

    <section class="dashboard-panel links-panel" aria-label="Bağlantı listesi">
        @if ($links->isEmpty())
            <h2>Henüz kısa bağlantın yok</h2>
            <a href="{{ route('dashboard') }}#baglanti-olustur">İlk bağlantını oluştur →</a>
        @else
            <div class="links-toolbar" id="links-toolbar" hidden>
                <label for="links-search">Bağlantılarda ara
                    <input id="links-search" class="dashboard-input" type="search" placeholder="Kısa bağlantı veya hedef adres">
                </label>
                <label for="links-sort">Sırala
                    <select id="links-sort" class="dashboard-input">
                        <option value="2:desc">En yeni</option>
                        <option value="2:asc">En eski</option>
                        <option value="3:desc">En çok tekil ziyaret</option>
                        <option value="3:asc">En az tekil ziyaret</option>
                        <option value="0:asc">Kısa bağlantı (A–Z)</option>
                        <option value="0:desc">Kısa bağlantı (Z–A)</option>
                        <option value="1:asc">Hedef adres (A–Z)</option>
                        <option value="1:desc">Hedef adres (Z–A)</option>
                    </select>
                </label>
                <label for="links-page-size">Sayfa başına
                    <select id="links-page-size" class="dashboard-input">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </label>
            </div>
                <table class="table dashboard-table links-table" id="table" role="table" aria-label="Kısa bağlantılarım">
                    <thead role="rowgroup"><tr role="row">
                        <th scope="col" role="columnheader">Kısa bağlantı</th><th scope="col" role="columnheader">Hedef adres</th><th scope="col" role="columnheader">Oluşturulma</th>
                        <th scope="col" role="columnheader">Tekil ziyaret</th><th scope="col" role="columnheader" class="links-actions"></th>
                    </tr></thead>
                    <tbody role="rowgroup">
                        @foreach ($links as $link)
                            <tr role="row">
                                <td role="cell" class="links-short"><a href="{{ route('direct', $link->short) }}" target="_blank" rel="noopener">/{{ $link->short }} <span aria-hidden="true">↗</span></a></td>
                                <td role="cell" class="links-target"><span class="links-cell-label" aria-hidden="true">Hedef adres</span><a href="{{ $link->url }}" target="_blank" rel="noopener" title="{{ $link->url }}">{{ $link->url }}</a></td>
                                <td role="cell" class="links-date" data-order="{{ $link->created_at?->timestamp }}"><span class="links-cell-label" aria-hidden="true">Oluşturulma</span>{{ $link->getDate() ?? '—' }}</td>
                                <td role="cell" class="links-views" data-order="{{ $link->visitorCount() }}"><span class="links-cell-label" aria-hidden="true">Tekil ziyaret</span>{{ $link->visitorCount() }}</td>
                                <td role="cell" class="links-actions">
                                    <a class="dashboard-button" href="{{ route('url.show', $link->short) }}">Detaylar <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        @endif
    </section>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script>
        if (document.getElementById('table')) {
            const table = $('#table').DataTable({
                dom: 't<"links-table-footer"ip>',
                autoWidth: false,
                orderMulti: false,
                order: [[2, 'desc']],
                columnDefs: [{ targets: 4, orderable: false, searchable: false }],
                language: { info: '_TOTAL_ bağlantıdan _START_–_END_', infoEmpty: 'Bağlantı yok', infoFiltered: '(toplam _MAX_)', zeroRecords: 'Eşleşen bağlantı yok', paginate: { previous: 'Önceki', next: 'Sonraki' } }
            });
            $('#links-toolbar').prop('hidden', false);
            $('#links-search').on('input', function () {
                table.search(this.value).draw();
            });
            $('#links-sort').on('change', function () {
                const [column, direction] = this.value.split(':');
                table.order([[Number(column), direction]]).draw();
            });
            $('#links-page-size').on('change', function () {
                table.page.len(Number(this.value)).draw();
            });
            table.on('order.dt', () => $('#links-sort').val(table.order()[0].join(':')));
        }

    </script>
@endsection
