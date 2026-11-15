@extends('layouts.auth')

@section('content')
    <x-auth.card :title="__('auth.registerBtn')" :description="__('auth.register_intro')" :action="route('register.post')" :logo="$settings->logoUrl()" wide>
        <div class="auth-grid">
            <x-auth.field name="name" :label="__('auth.name')" autocomplete="given-name" minlength="2" maxlength="191" />
            <x-auth.field name="surname" :label="__('auth.surname')" autocomplete="family-name" minlength="2" maxlength="191" />
            <x-auth.field name="email" :label="__('auth.email')" type="email" autocomplete="email" maxlength="191" />
            <x-auth.field name="username" :label="__('auth.username')" autocomplete="username" minlength="3" maxlength="191" />
            <x-auth.field name="password" :label="__('auth.password')" :hint="__('auth.password_minimum')" type="password" autocomplete="new-password" minlength="8" maxlength="72" />
            <x-auth.field name="password_confirmation" :label="__('auth.passwordcheck')" type="password" autocomplete="new-password" />
        </div>

        @if ($settings->login)
            <x-slot:footer>
                @lang('homepage.registerQuestion')
                <a href="{{ route('login') }}">@lang('auth.loginBtn')</a>
            </x-slot:footer>
        @endif
    </x-auth.card>
@endsection
