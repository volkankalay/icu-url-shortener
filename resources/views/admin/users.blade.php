@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">
@endpush

@section('dashboardcontent')
    @php($title = $suspended ? 'Dondurulan kullanıcılar' : 'Kullanıcılar')
    <header class="dashboard-heading">
        <div><h1>{{ $title }}</h1><p>{{ $users->count() }} kullanıcı</p></div>
        <a class="dashboard-button" href="{{ route($suspended ? 'admin.users' : 'admin.users.trash') }}">
            {{ $suspended ? 'Aktif kullanıcılar' : 'Dondurulan kullanıcılar' }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
    </header>

    @if ($errors->any())
        <p class="dashboard-panel" role="alert">{{ $errors->first() }}</p>
    @endif

    <x-dashboard.admin-table :label="$title" :empty="$users->isEmpty() ? 'Gösterilecek kullanıcı yok.' : null"
        :sort-options="['0:asc' => 'Ad (A–Z)', '0:desc' => 'Ad (Z–A)', '1:asc' => 'E-posta (A–Z)', '1:desc' => 'E-posta (Z–A)', '2:asc' => 'Hesap türü (A–Z)', '2:desc' => 'Hesap türü (Z–A)']">
        <x-slot:head>
            <th scope="col" role="columnheader">Kullanıcı</th>
            <th scope="col" role="columnheader">E-posta</th>
            <th scope="col" role="columnheader">Hesap türü</th>
            <th scope="col" role="columnheader" class="links-actions">İşlemler</th>
        </x-slot:head>
        @foreach ($users as $user)
            <tr role="row">
                <td role="cell" class="admin-wide-cell" data-order="{{ $user->full_name }}">
                    <strong>{{ $user->full_name }}</strong><small class="admin-secondary">{{ '@' . $user->username }}</small>
                </td>
                <td role="cell" class="admin-wide-cell"><span class="links-cell-label" aria-hidden="true">E-posta</span>{{ $user->email }}</td>
                <td role="cell"><span class="links-cell-label" aria-hidden="true">Hesap türü</span><span class="admin-type-badge">{{ $user->user_type->label() }}</span></td>
                <td role="cell" class="links-actions">
                    @if ($suspended || $user->id !== auth()->id())
                        <div class="admin-table-actions">
                            @unless ($suspended)
                                <button type="button" class="dashboard-button" data-toggle="modal" data-target="#user-type-modal"
                                        data-username="{{ $user->username }}" data-user-type="{{ $user->user_type->value }}">Yetkiyi düzenle</button>
                            @endunless
                            <button type="button" class="dashboard-button" data-toggle="modal" data-target="#user-status-modal"
                                    data-user-id="{{ $user->id }}" data-username="{{ $user->username }}">{{ $suspended ? 'Aktif et' : 'Dondur' }}</button>
                        </div>
                    @else
                        <small class="admin-secondary">Senin hesabın</small>
                    @endif
                </td>
            </tr>
        @endforeach
    </x-dashboard.admin-table>

    @include('modals.UserStatus')
    @unless ($suspended)
        @include('modals.userBeMod')
    @endunless
@endsection

@section('js')
    @if ($users->isNotEmpty())
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/admin-table.js') }}"></script>
        <script>
            $('#user-status-modal').on('show.bs.modal', (event) => {
                const user = event.relatedTarget.dataset;
                $('#status-user-id').val(user.userId);
                $('#status-username').text(user.username);
            });
            $('#user-type-modal').on('show.bs.modal', (event) => {
                const user = event.relatedTarget.dataset;
                $('#type-username').val(user.username);
                $('#roleSelector').val(user.userType);
            });
        </script>
    @endif
@endsection
