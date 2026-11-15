@extends('layouts.auth')

@section('content')
    <x-auth.card :title="__('auth.loginBtn')" :description="__('auth.login_intro')" :action="route('login.post')" :logo="$settings->logoUrl()">
        <x-auth.field name="username" :label="__('auth.usernameoremail')" autocomplete="username" maxlength="191" />
        <x-auth.field name="password" :label="__('auth.password')" type="password" autocomplete="current-password" />

        <label class="auth-remember">
            <input type="checkbox" name="rememberme" value="1" @checked(old('rememberme'))>
            @lang('auth.rememberme')
        </label>

        @if ($settings->register)
            <x-slot:footer>
                @lang('homepage.loginQuestion')
                <a href="{{ route('register') }}">@lang('auth.registerBtn')</a>
            </x-slot:footer>
        @endif
    </x-auth.card>
@endsection
