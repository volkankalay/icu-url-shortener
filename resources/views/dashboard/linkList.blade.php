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
      Linklerim
    </div>
  <div class="table-responsive">
    <table class="table table-striped  table-bordered" id="table">
      <thead>
        <tr>
          <th scope="col">Kısa Adres</th>
          <th scope="col">Hedef Adres</th>
          <th scope="col">Oluşturulma</th>
          <th scope="col text-right">Gösterim</th>
          <th scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($links as $link)
        <tr>
          <td><a href="{{route('direct',$link->short)}}" target="_blank" class="text-dark text-decoration-none" id="ourlink">
            {{$settings->site.'/'.$link->short}} <i class="fas fa-xs fa-pull-right fa-external-link-alt"></i>
          </td>
          <td title="{{$link->url}}">{{Str::limit($link->url,33)}}
            <a href="{{$link->url}}" target="_blank" class="text-dark text-decoration-none">
              <i class="fas fa-xs fa-pull-right fa-external-link-alt"></i>
            </a>
          </td>
          <td>{{$link->getDate()}}</td>
          <td class="text-right">{{$link->visitorCount()}}</td>
          <td class="text-center">
            <a href="{{route('url.show',$link->short)}}" class="text-decoration-none">
              <button class="btn btn-light border shadow-hover-sm">Detaylar</button>
            </a>
            <button class="btn btn-primary border shadow-hover-sm duzenleBtn" link="{{$link->short}}">Düzenle</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

</section>
@include('modals.LinkExtensionChange')

@endsection
@section('js')
<script src="{{asset('js')}}/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$('#table').DataTable({
    responsive: true
});

$("#table").on("click", ".duzenleBtn", function (){
    shorten = $(this)[0].getAttribute('link');
    $('#shortold').val(shorten);
    $('#shortnew').val(shorten);
    $('#updateModal').modal();
});
</script>
@endsection
