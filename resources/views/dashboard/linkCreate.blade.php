@extends('dashboard')
@section('dashboardcontent')
<section>

  @if($errors->any())
  <div class="card col-lg-6 text-white bg-light mx-auto mt-4">
    <div class="card-body">
      <h5 class="card-title m-auto text-center">
        <a href="{{route('direct',$errors->first())}}" class="text-dark text-decoration-none" target="_blank">
          {{$settings->site.'/'.$errors->first()}}
        </a>
      </h5>
    </div>
  </div>
  @endif

  <div class="card border-0 p-5">
    <div class="text-center text-primary h1 my-4">
      Link Ekle
    </div>
    <form method="post" action="{{route('url.create.post')}}">
      @csrf
      <input name="user" type="number" value="{{Auth::user()->id}}" hidden>
      <div class="row p-5">
        <div class="col-lg-10 my-2">
          <input name="url" class="form-control form-control-lg text-primary" type="url" placeholder="https://www.google.com" required>
        </div>
        <div class="col-lg-2 my-2 text-center">
          <button type="submit" class="btn btn-lg btn-light border">@lang('homepage.shortbtn')</button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
