@extends('layouts.master')
@section('content')
    <!-- Masthead -->
    <header class="masthead text-white text-center">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">@lang('homepage.slogan')</h1>
          </div>
          <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
            <form @auth method="post"  action="{{route('url.create.post')}}" @endif>
              @auth
                @csrf
                <input name="user" type="number" value="{{Auth::user()->id}}" hidden>
              @endif
              <div class="form-row">
                <div class="col-12 col-md-9 mb-2 mb-md-0">
                  <input name="url" type="url" class="form-control form-control-lg" placeholder="@lang('homepage.linkplaceholder')" required>
                </div>
                <div class="col-12 col-md-3">
                  @auth
                  <button type="submit" class="btn btn-block btn-lg btn-primary">@lang('homepage.shortbtn')</button>
                  @else
                  <button type="button" class="btn btn-block btn-lg btn-primary" data-toggle="modal" data-target="#loginOrRegister">@lang('homepage.shortbtn')</button>
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </header>
  <!-- Icons Grid -->
  <section class="features-icons bg-light text-center">
    <div class="container-fluid">
      <div class="row justify-content-around">
        <div class="col-lg-3 p-4 shadow-hover rounded">
          <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-screen-desktop m-auto text-primary"></i>
            </div>
            <h3 class="text-warning">@lang('homepage.details')</h3>
            <p class="lead mb-0">@lang('homepage.detailstxt')</p>
          </div>
        </div>
        <div class="col-lg-3 p-4 shadow-hover rounded">
          <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-layers m-auto text-primary"></i>
            </div>
            <h3 class="text-warning">@lang('homepage.layer')</h3>
            <p class="lead mb-0">@lang('homepage.layertxt')</p>
          </div>
        </div>
        <div class="col-lg-3 p-4 shadow-hover rounded">
          <div class="features-icons-item mx-auto mb-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-check m-auto text-primary"></i>
            </div>
            <h3 class="text-warning">@lang('homepage.easy')</h3>
            <p class="lead mb-0">@lang('homepage.easytxt')</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="call-to-action text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 mx-auto">
          <h2 class="mb-4">@lang('homepage.readyquestion')</h2>
        </div>
        <div class="col-md-3 mx-auto">
          <a href="{{route('register')}}" class="btn btn-block btn-lg btn-primary">@lang('homepage.register') !</a>
        </div>
      </div>
    </div>
  </section>

@include('widgets.loginOrRegister')
@endsection
