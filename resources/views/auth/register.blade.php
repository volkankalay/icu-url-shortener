@extends('layouts.master')
@section('content')
<section class="call-to-action">
  <h1 class="text-center text-white shadow-lg py-2">@lang('auth.registerBtn')</h1>
  <div class="card my-5 p-5 col-lg-8 mx-auto shadow">
          @if($errors->any())
          <div class="alert alert-danger">
            @foreach($errors->all() as $error)
              <p>{{$error}}</p>
            @endforeach
          </div>
          @endif
  <form method="post" class="text-center" action="{{route('register.post')}}">
    @csrf
    <div class="row">
      <div class="col-md-6">
        <div class="form-group m-4">
          <input name="name" type="text" class="form-control mx-auto shadow" id="name" placeholder="@lang('auth.name')" required>
        </div>
        <div class="form-group m-4">
          <input name="surname" type="text" class="form-control mx-auto shadow" id="surname" placeholder="@lang('auth.surname')" required>
        </div>
        <div class="form-group m-4">
          <input name="email" type="email" class="form-control mx-auto shadow" id="email" placeholder="@lang('auth.email')" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group m-4">
          <input name="username" type="text" class="form-control mx-auto shadow" id="username" placeholder="@lang('auth.username')" required>
        </div>
        <div class="form-group m-4">
          <input name="password" type="password" class="form-control mx-auto shadow" id="password" placeholder="@lang('auth.password')" required>
        </div>
        <div class="form-group m-4">
          <input name="password_confirmation" type="password" class="form-control mx-auto shadow" id="password" placeholder="@lang('auth.passwordcheck')" required>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-light border shadow btn-block col-md-4 offset-md-4 p-2">@lang('auth.registerBtn')</button>
  </form>
</div>

<div class="text-center text-white h3 ">
  @lang('homepage.registerQuestion') <a href="{{route('login')}}" class=" text-white">@lang('homepage.registerQuestionYes')</a>
</div>

</section>
@endsection
