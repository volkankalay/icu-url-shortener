@extends('layouts.master')
@section('content')
<header class="masthead" style="padding-top:3rem!important;padding-bottom:3rem!important;">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-2 my-auto">
        <div class="card mx-1 py-3 px-2">

          <ul class="nav flex-column text-center">

            <li class="nav-item">
              <a class="nav-link @if(Request::segment(1)=='dashboard' && Request::segment(2)==NULL) text-dark font-weight-bolder @endif" href="{{route('dashboard')}}">
                @lang('homepage.dashboard')
              </a>
            </li>

        <div class="dropdown-divider"></div>

            <li class="nav-item">
              <a class="nav-link @if(Request::segment(2)=='link') text-dark font-weight-bolder @endif" href="{{route('url.create')}}">Link Oluştur</a>
            </li>

        <div class="dropdown-divider"></div>

            <li class="nav-item">
              <a class="nav-link @if(Request::segment(2)=='links') text-dark font-weight-bolder @endif" href="{{route('url.list')}}">Linklerim</a>
            </li>

        <div class="dropdown-divider"></div>

            <li class="nav-item">
              <a class="nav-link @if(Request::segment(1)=='profile') text-dark font-weight-bolder @endif" href="{{route('profile.index')}}">Profilim</a>
            </li>


@if(Auth::user()->role==1)
        <div class="dropdown-divider"></div>

            <li class="nav-item">
              <a class="nav-link text-danger" href="{{route('admin.settings')}}">Site Ayarları</a>
            </li>

        <div class="dropdown-divider"></div>

          <li class="nav-item">
            <a class="nav-link text-danger" href="{{route('admin.users')}}">Kullanıcı Listesi</a>
          </li>

        <div class="dropdown-divider"></div>

          <li class="nav-item">
            <a class="nav-link text-danger" href="{{route('admin.links')}}">Link Listesi</a>
          </li>

@endif

          </ul>
        </div>
      </div>

      <div class="col-lg-10 my-1">

        <div class="card mx-1 my-auto py-3 px-2">
            @yield('dashboardcontent')
        </div>

      </div>

    </div>
  </div>
</header>
@endsection
