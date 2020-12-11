<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="keywords" content="{{$settings->keywords}}">
  <meta name="description" content="{{$settings->description}}">
  <meta name="author" content="Volkan Kalay">
  <link rel="icon" type="image/x-icon" href="{{$settings->favicon}}" />
    <title>{{$settings->title}}</title>
  <link href="{{asset('vendor')}}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{asset('vendor')}}/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="{{asset('vendor')}}/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
  <link href="{{asset('css')}}/landing-page.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/49b41ce5ec.js" crossorigin="anonymous"></script>
  @yield('css')

  @toastr_css

{{$settings->header_code}}
</head>
<a id="topBtn" href="#top" class="go-top"><i class="fa fa-chevron-up"></i></a>
<div id="top" ></div>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container">
      <a class="navbar-brand text-dark" href="{{route('home')}}">
        <img src="{{$settings->logo}}" class="img-responsive" width="36px"> {{$settings->title}}</a>

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
            <div >
              <a href="{{route('login')}}" class="btn btn-light border p-2">@lang('homepage.login')</a>
              <a href="{{route('register')}}" class="btn btn-light border p-2">@lang('homepage.register')</a>
            </div>
          @endif
    </div>
  </nav>
