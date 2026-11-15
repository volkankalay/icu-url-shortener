@extends('dashboard')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('dashboardcontent')
    <header class="dashboard-heading">
        <div><h1>Site ayarları</h1><p>Site kimliğini, marka görsellerini ve kayıt erişimini yönet.</p></div>
        <button type="submit" form="site-settings" class="dashboard-button dashboard-primary"><i class="fas fa-save" aria-hidden="true"></i> Değişiklikleri kaydet</button>
    </header>

    <form id="site-settings" class="admin-settings-grid" action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <section class="dashboard-panel admin-settings-fields" aria-labelledby="site-identity-title">
            <h2 id="site-identity-title">Site kimliği</h2>
            <x-auth.field name="site" label="Site adresi" type="url" :default="$settings->site" maxlength="191" />
            <x-auth.field name="title" label="Site başlığı" :default="$settings->title" :required="false" maxlength="191" />
            <x-auth.field name="keywords" label="Anahtar kelimeler" :default="$settings->keywords" :required="false" hint="Kelimeleri virgülle ayır." maxlength="191" />
            <div class="auth-field">
                <label for="description">Site açıklaması</label>
                <textarea name="description" id="description" class="dashboard-input" rows="3" maxlength="191" @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description', $settings->description) }}</textarea>
                @error('description')
                    <p id="description-error" class="auth-field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <div class="admin-settings-stack">
            <section class="dashboard-panel admin-settings-fields" aria-labelledby="site-brand-title">
                <h2 id="site-brand-title">Marka görselleri</h2>
                <p class="dashboard-muted">Yeni dosya seçmezsen mevcut görsel kullanılır. Dosya başına en fazla 2 MB.</p>
                @foreach (['logo' => ['Logo', $settings->logoUrl()], 'favicon' => ['Favicon', $settings->faviconUrl()]] as $field => [$label, $image])
                    <div class="admin-brand-field">
                        <img src="{{ $image }}" width="48" height="48" alt="Mevcut {{ $label }}">
                        <div class="auth-field">
                            <label for="{{ $field }}">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="file" class="dashboard-input" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp" @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                            @error($field)
                                <p id="{{ $field }}-error" class="auth-field-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="dashboard-panel admin-settings-fields" aria-labelledby="site-membership-title">
                <h2 id="site-membership-title">Üyelik</h2>
                <input type="hidden" name="register" value="0">
                <label class="auth-remember">
                    <input type="checkbox" name="register" value="1" @checked(old('register', $settings->register))>
                    Yeni kullanıcı kaydına izin ver
                </label>
                @error('register')
                    <p class="auth-field-error" role="alert">{{ $message }}</p>
                @enderror
            </section>
        </div>

        <section class="dashboard-panel admin-settings-fields admin-settings-wide" aria-labelledby="site-advanced-title">
            <h2 id="site-advanced-title">Gelişmiş</h2>
            <div class="admin-settings-grid">
                @foreach (['header_code' => 'Üst bölüm kodu', 'footer_code' => 'Alt bölüm kodu'] as $field => $label)
                    <div class="auth-field">
                        <label for="{{ $field }}">{{ $label }}</label>
                        <textarea name="{{ $field }}" id="{{ $field }}" class="dashboard-input admin-settings-code" rows="5" spellcheck="false" @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>{{ old($field, $settings->{$field}) }}</textarea>
                        @error($field)
                            <p id="{{ $field }}-error" class="auth-field-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        </section>
    </form>
@endsection
