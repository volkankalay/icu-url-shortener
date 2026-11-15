@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('dashboardcontent')
    <header class="dashboard-heading">
        <div>
            <h1>@lang('auth.profile')</h1>
            <p>Hesap bilgilerini ve şifreni yönet.</p>
        </div>
    </header>

    <div class="profile-grid">
        <section class="dashboard-panel profile-identity" aria-labelledby="profile-identity-title">
            <div class="profile-identity-heading">
                <span class="profile-avatar" aria-hidden="true">{{ Str::upper(Str::substr($profile->name, 0, 1) . Str::substr($profile->surname, 0, 1)) }}</span>
                <div>
                    <h2 id="profile-identity-title">{{ $profile->full_name }}</h2>
                    <p class="dashboard-muted">{{ '@' . $profile->username }}</p>
                </div>
                <span class="profile-type-badge" aria-label="Hesap türü: {{ $profile->user_type->label() }}">{{ $profile->user_type->label() }}</span>
            </div>
            <dl class="dashboard-facts profile-facts">
                <div class="profile-email"><dt>@lang('auth.email')</dt><dd>{{ $profile->email }}</dd></div>
                <div><dt>Kayıt tarihi</dt><dd>{{ $profile->getCreatedDate() ?? '—' }}</dd></div>
                <div><dt>Aktif bağlantı</dt><dd><a href="{{ route('url.list') }}">{{ $linkCount }}</a></dd></div>
            </dl>
        </section>

        <section class="dashboard-panel profile-security" aria-labelledby="profile-security-title">
            <h2 id="profile-security-title">Şifre değiştir</h2>
            <form class="dashboard-form profile-password-form" action="{{ route('profile.pass') }}" method="post">
                @csrf
                <x-auth.field name="current_password" label="Mevcut şifre" type="password" autocomplete="current-password" error-bag="passwordChange" />
                <div class="profile-password-fields">
                    <x-auth.field name="password" label="Yeni şifre" type="password" autocomplete="new-password" minlength="8" maxlength="72" :hint="__('auth.password_minimum')" error-bag="passwordChange" />
                    <x-auth.field name="password_confirmation" label="Yeni şifreyi doğrula" type="password" autocomplete="new-password" minlength="8" maxlength="72" error-bag="passwordChange" />
                </div>
                <button type="submit" class="dashboard-button dashboard-primary">Şifreyi güncelle</button>
            </form>
        </section>

        <section class="dashboard-panel profile-close" aria-labelledby="profile-close-title">
            <div>
                <h2 id="profile-close-title">Hesabı kapat</h2>
                <p class="dashboard-muted">Hesabın devre dışı kalır ve oturumun kapanır.</p>
            </div>
            <button type="button" class="dashboard-button profile-close-button" data-toggle="modal" data-target="#deleteModal">Hesabı kapat</button>
        </section>
    </div>

    @include('modals.ProfileDeleteAccount')
@endsection

@section('js')
    <script src="{{ asset('js/auth.js') }}"></script>
    @if ($errors->accountDeletion->any())
        <script>$('#deleteModal').modal('show');</script>
    @endif
@endsection
