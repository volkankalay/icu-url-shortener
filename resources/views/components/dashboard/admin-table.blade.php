@props(['label', 'empty', 'sortOptions'])

<section class="dashboard-panel links-panel admin-table-panel" aria-label="{{ $label }}">
    @if ($empty)
        <p class="dashboard-muted">{{ $empty }}</p>
    @else
        <div class="links-toolbar" data-admin-toolbar hidden>
            <label>Ara
                <input class="dashboard-input" type="search" data-admin-search>
            </label>
            <label>Sırala
                <select class="dashboard-input" data-admin-sort>
                    @foreach ($sortOptions as $value => $caption)
                        <option value="{{ $value }}">{{ $caption }}</option>
                    @endforeach
                </select>
            </label>
            <label>Sayfa başına
                <select class="dashboard-input" data-admin-page-size>
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <table class="table dashboard-table links-table admin-table" role="table" aria-label="{{ $label }}" data-admin-table>
            <thead role="rowgroup"><tr role="row">{{ $head }}</tr></thead>
            <tbody role="rowgroup">{{ $slot }}</tbody>
        </table>
    @endif
</section>
