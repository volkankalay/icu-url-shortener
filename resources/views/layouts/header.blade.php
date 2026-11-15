<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="keywords" content="{{$settings->keywords}}">
  <meta name="description" content="{{$settings->description}}">
  <meta name="author" content="Volkan Kalay">
  <link rel="icon" href="{{ $settings->faviconUrl() }}">
    <title>{{$settings->title}}</title>
  <link href="{{asset('vendor')}}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('vendor/fontawesome-free/css/fontawesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">
  <style>
    :root {
      @foreach (\App\Support\Theme::COLORS['light'] as $role => $color)
        --theme-{{ $role }}: {{ $color }};
      @endforeach
    }
    :root[data-theme="dark"] {
      color-scheme: dark;
      @foreach (\App\Support\Theme::COLORS['dark'] as $role => $color)
        --theme-{{ $role }}: {{ $color }};
      @endforeach
    }
  </style>
  @hasSection('body-class')
    <script src="{{ asset('js/theme.js') }}"></script>
  @endif
  @yield('css')


{{$settings->header_code}}
</head>
<body id="top" class="@yield('body-class')">
  <!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container">
      <a class="navbar-brand text-dark" href="{{route('home')}}">
        <img src="{{ $settings->logoUrl() }}" alt="" class="img-responsive" width="36" height="36"> {{$settings->title}}</a>

      <div class="nav-actions">
          @hasSection('body-class')
            <button id="theme-toggle" class="btn btn-light theme-toggle" type="button"
                    aria-label="{{ __('homepage.dark_mode') }}" aria-pressed="false" hidden>
              <i class="fas fa-moon" aria-hidden="true"></i>
              <i class="fas fa-sun" aria-hidden="true"></i>
            </button>
          @endif
          @auth
            <div class="dropdown">
              <button class="btn btn-light border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{Auth::user()->username}}
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item font-weight-light text-muted text-right" href="{{route('dashboard')}}">
                    @lang('homepage.dashboard')
                  </a>
                  <a class="dropdown-item font-weight-light text-muted text-right" href="{{route('profile.index')}}">
                    @lang('auth.profile')
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item font-weight-light text-muted text-right" href="{{route('logout')}}">
                    @lang('auth.logout')
                  </a>
              </div>
            </div>
          @else
              @if($settings->login==1)
                <a href="{{route('login')}}" class="btn btn-light border p-2">@lang('homepage.login')</a>
              @endif
              @if($settings->register==1)
                <a href="{{route('register')}}" class="btn btn-light border p-2 nav-register">@lang('homepage.register')</a>
              @endif
          @endif
      </div>
    </div>
  </nav>
