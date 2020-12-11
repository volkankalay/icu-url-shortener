@extends('dashboard')
@section('dashboardcontent')

  <h3 class="px-2">@lang('auth.profile')</h3>
          <div class="dropdown-divider"></div>

@if($errors->any())
<div class="card col-lg-6 text-white bg-light mx-auto mt-4">
  <div class="card-body">
    <h5 class="card-title m-auto text-center">
      <span class="text-dark text-decoration-none">
        {{$errors->first()}}
      </span>
    </h5>
  </div>
</div>
@endif
<div class="row">

  <div class="col-lg-6 my-3">
    <div class="card mb-3">
      <div class="row no-gutters">
        <div class="col-md-4 p-4 m-auto border-right">
          <img src="{{asset('img/user.svg')}}" class="card-img" alt="profile" width="100px">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">{{$profile->username}}</h5>
            <p class="card-text">
              <span class="font-weight-bolder">Ad:</span> {{$profile->name}}
            </p>
            <p class="card-text">
              <span class="font-weight-bolder">Soyad:</span> {{$profile->surname}}
            </p>
            <p class="card-text">
              <span class="font-weight-bolder">E-Posta:</span> {{$profile->email}}
            </p>
            <p class="card-text">
              <span class="font-weight-bolder">Kısaltma:</span> {{$profile->linkCount()}}
            </p>

            <p class="card-text"><small class="text-muted">Kayıt: {{$profile->getCreatedDate()}}</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 my-3">

      <div class="list-group px-4">

        <button class="list-group-item list-group-item-action bg-primary text-white" id="changePassword">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">Şifre Değiştir</h6>
            <small><i class="fa fa-lock"></i></small>
          </div>
          <small>Giriş şifrenizi değiştirebilirsiniz.</small>
        </button>

        <button class="list-group-item list-group-item-action bg-light" disabled>
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">E-Posta Değiştir</h6>
            <small><i class="fa fa-paper-plane"></i></small>
          </div>
          <small>E-Posta değiştirme devre dışıdır.</small>
        </button>

        <button class="list-group-item list-group-item-action bg-danger text-white" id="deleteBtn">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">Hesabı Sil</h6>
            <small><i class="fa fa-trash"></i></small>
          </div>
          <small>Dikkat bu işlemin geri dönüşü yoktur.</small>
        </button>
      </div>

  </div>

</div>


@include('modals.ProfilePasswordChange')
@include('modals.ProfileDeleteAccount')

@endsection
@section('js')
<script type="text/javascript">
$('#changePassword').click(function(){
  $('#passwordUpdateModal').modal();
});
$('#deleteBtn').click(function(){
  $('#deleteModal').modal();
});
</script>
@endsection
