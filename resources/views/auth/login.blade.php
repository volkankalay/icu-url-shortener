@extends('layouts.master')
@section('content')
<section class="call-to-action">
  <h1 class="text-center text-white shadow-lg py-2">@lang('auth.loginBtn')</h1>
  <div class="card my-5 p-5 col-lg-6 mx-auto shadow">

    <form method="post" action="{{route('login.post')}}">
      @csrf
      <div class="form-group my-2 ">
        <input name="username" type="text" class="form-control shadow" id="username" placeholder="@lang('auth.usernameoremail')" required>
      </div>
      <div class="form-group my-2 ">
        <input name="password" type="password" class="form-control shadow" id="password" placeholder="@lang('auth.password')" required>
      </div>
      <div class="form-group my-2 form-check ml-2">
        <input name="rememberme" type="checkbox" class="form-check-input shadow" id="rememberme">
        <label class="form-check-label" for="rememberme">@lang('auth.rememberme')</label>
      </div>
      <div class="text-center my-2 float-right">
        <button type="submit" class="btn btn-light border btn-lg shadow">@lang('auth.loginBtn')</button>
      </div>
    </form>
  </div>

  <div class="text-center text-white h3">
    @lang('homepage.loginQuestion') <a href="{{route('register')}}" class="text-white">@lang('homepage.loginQuestionYes')</a>
  </div>
</section>
@endsection
