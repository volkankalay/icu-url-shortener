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
      Link Listesi
    </div>
  <div class="table-responsive">
    <table class="table table-striped  table-bordered"id="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Sahip</th>
          <th scope="col">Kısaltılmış</th>
          <th scope="col">Hedef</th>
          <th scope="col">Tıklanma</th>
          <th scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($links as $link)
        <tr>
          <td>{{$link->id}}</td>
          <td>{{$link->getUser->username}}</td>
          <td data-content="Kopyalandı!" id="copylink" onclick="copyToClipboard('#copylink')" data-toggle="popover" data-placement="top">
            {{$settings->site.'/'.$link->short}}
            <a href="{{route('direct',$link->short)}}" target="_blank" class="text-dark text-decoration-none" id="ourlink">
              <i class="fas fa-xs fa-pull-right fa-external-link-alt"></i>
            </a>
          </td>
          <td>
            {{Str::limit($link->url,50)}}
            <a href="{{url($link->url)}}" target="_blank" class="text-dark text-decoration-none" id="ourlink">
              <i class="fas fa-xs fa-pull-right fa-external-link-alt"></i>
            </a>
          </td>
          <td>{{$link->visitorCount()}}</td>

          <td class="text-center">
            <a href="{{route('url.show',$link->short)}}" class="text-decoration-none">
              <button class="btn btn-primary btn-sm border shadow-hover-sm btn-block">Detaylar</button>
            </a>
            <button class="btn btn-danger btn-sm btn-block forceDeleteBtn" force-id="{{$link->id}}" force-short="{{$link->short}}">
              Sil
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

</section>
@include('modals.LinkForceDelete')

@endsection
@section('js')
<script src="{{asset('js')}}/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$('#table').DataTable({
    responsive: true
});

$("#table").on("click", ".forceDeleteBtn", function (){
    id = $(this)[0].getAttribute('force-id');
    st = $(this)[0].getAttribute('force-short');
    $('#forceid').val(id);
    $('#yazdirun').html(st);
    $('#forceDeleteModal').modal();

});
</script>
@endsection
