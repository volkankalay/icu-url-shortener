@php
$profile=Auth::user();
@endphp

@extends('dashboard')
@section('dashboardcontent')

  <h3 class="px-2">@lang('homepage.dashboard')</h3>
          <div class="dropdown-divider"></div>

  <div class="alert alert-success alert-dismissible fade show m-3" role="alert" data-dismiss="alert" aria-label="Close">
    <strong>Hoşgeldin</strong> {{Auth::user()->name.' '.Auth::user()->surname}} !
    <p class="my-1"><strong>Son Girişin:</strong> {{$lastlogin->getDate()}}</p>
    <p class="my-1"><strong>Tarayıcı / OS:</strong> {{$lastlogin->getBrowser().' / '.$lastlogin->getOs()}}</p>
  </div>

  <div class="container-fluid my-3">
    @if($profile->linkCount()>0)
    <div class="card">
      <h3 class="stroke-primary text-white text-center p-3 border-bottom">Hesabında toplam <span class="stroke-success">{{$profile->linkCount()}}</span> adet kısaltılmış adres var.</h3>
      <h3 class="stroke-success text-white text-center p-3">Görüntülenme</h3>

    <div class="container">
      <div class="row justify-content-center my-3">
        <div class="col-lg-3 text-center">
          <div class="card-header p-3 border text-primary h3">
            Bugün
          </div>
          <div class="card-text p-4 text-center border h3 text-success">
            {{$todayCount}}
          </div>
        </div>
          <div class="col-lg-3 text-center">
          <div class="card-header p-3 border text-primary h3">
            Bu Ay
          </div>
          <div class="card-text p-4 text-center border h3 text-success">
          {{$thisMonthCount}}
        </div>
        </div>
          <div class="col-lg-3 text-center">
          <div class="card-header p-3 border text-primary h3">
            Toplam
          </div>
          <div class="card-text p-4 text-center border h3 text-success">
        {{$linkViewCount}}
      </div>
        </div>
      </div>
    </div>

      <div class="card-footer text-center">
        <a href="{{route('url.list')}}" class="btn btn-primary">Adreslerim</a>
      </div>
    </div>
    @else
    <div class="card">
      <h3 class="stroke-danger text-white text-center p-3">Hesabında hiç kısaltılmış adres yok.</h3>
      <div class="card-footer text-center">
        <a href="{{route('url.create')}}" class="btn btn-success">Yeni Oluştur</a>
      </div>
    </div>
    @endif

  </div>
@endsection
