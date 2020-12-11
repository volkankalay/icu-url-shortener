@extends('dashboard')
@section('css')
<link href="{{asset('css')}}/jquery.dataTables.css" rel="stylesheet">
@endsection
@section('dashboardcontent')

  @if($errors->any())
  <div class="card col-lg-6 text-white bg-light mx-auto mt-4">
    <div class="card-body">
      <h5 class="card-title m-auto text-center text-dark">
          {{$errors->first()}}
      </h5>
    </div>
  </div>
  @endif
<section>
  <div class="card border-0 p-5">
    <div class="text-center text-primary h1 my-4">
      Kullanıcılar <h4><span class="badge badge-primary">{{$users->count()}}</span></h4>
    </div>
  <div class="table-responsive">
    <table class="table table-striped  table-bordered"  id="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Ad</th>
          <th scope="col">Soyad</th>
          <th scope="col">Kullanıcı Adı</th>
          <th scope="col">E-Posta</th>
          <th scope="col">Rol</th>
          <th scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{$user->id}}</td>
          <td>{{$user->name}}</td>
          <td>{{$user->surname}}</td>
          <td>{{$user->username}}</td>
          <td>{{$user->email}}</td>
          <td>
            @if($user->role==1)
              <span class="text-danger">Yönetici</span>
              @elseif($user->role==2)
              <span class="text-primary">Editör</span>
              @else
              <span>Üye</span>
            @endif
          </td>
          <td class="text-center">
            @if($user->id!=Auth::user()->id)
              <button class="btn btn-warning text-white btn-sm suspendBtn" suspend-id="{{$user->id}}" suspend-username="{{$user->username}}">
                Dondur
              </button>

              <button class="btn btn-danger btn-sm roleChange" userid="{{$user->id}}" username="{{$user->username}}" rol="{{$user->role}}">
                Yetki
              </button>
             @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <a href="{{route('admin.users.trash')}}" class="btn btn-info text-white m-auto">Üyeliği Dondurulanlar</a>
</div>

</section>
@include('modals.userSuspend')
@include('modals.userBeMod')

@endsection
@section('js')
<script src="{{asset('js')}}/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$('#table').DataTable({
    responsive: true
});


$("#table").on("click", ".suspendBtn", function (){
    id = $(this)[0].getAttribute('suspend-id');
    un = $(this)[0].getAttribute('suspend-username');
    $('#suspendid').val(id);
    $('#yazdirid').html(un);
    $('#suspendModal').modal();
});


$("#table").on("click", ".roleChange", function (){
    id = $(this)[0].getAttribute('userid');
    un = $(this)[0].getAttribute('username');
    rol = $(this)[0].getAttribute('rol');
    $('#roleChangeId').val(id);
    $('#roleChangeUn').val(un);
    if(rol==1){
      $('#roleChangeRol').html("Yönetici");
    } else if (rol==2) {
      $('#roleChangeRol').html("Editör");
    } else{
      $('#roleChangeRol').html("Üye");
    }
    $('#roleChangeModal').modal();
});

</script>
@endsection
