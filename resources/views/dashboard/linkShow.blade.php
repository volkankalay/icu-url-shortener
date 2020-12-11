@extends('dashboard')
@section('css')
<link href="{{asset('css')}}/jquery.dataTables.css" rel="stylesheet">
@endsection
@section('dashboardcontent')
<section>

  <div class="card border-0 p-5">

    <div class="row">
      <div class="col-lg-12 mx-auto shadow-hover my-1 border rounded bg-img-4" data-content="Kopyalandı!" id="copylink" onclick="copyToClipboard('#copylink')" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">{{$settings->site.'/'.$url->short}}</h4>
      </div>
    </div>

    <div class="row mt-2 justify-content-around text-center">
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-1" data-content="Çoğul Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Bugün</h4>
          <br>
        <b class="card-txt">{{$dailyCount}}</b>
      </div>
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-1" data-content="Çoğul Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Bu ay</h4>
          <br>
        <b class="card-txt">{{$monthlyCount}}</b>
      </div>
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-1" data-content="Çoğul Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Toplam</h4>
          <br>
        <b class="card-txt">{{$visitorCount}}</b>
      </div>
    </div>

    <div class="row mt-2 justify-content-around text-center">
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-2" data-content="Tekil Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Bugün</h4>
          <br>
        <b class="card-txt">{{$dailyCountX}}</b>
      </div>
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-2" data-content="Tekil Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Bu ay</h4>
          <br>
        <b class="card-txt">{{$monthlyCountX}}</b>
      </div>
      <div class="col-lg-4 p-3 shadow-hover my-1 border rounded bg-img-2" data-content="Tekil Ziyaretçi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">Toplam</h4>
          <br>
        <b class="card-txt">{{$visitorCountX}}</b>
      </div>
    </div>

    <div class="row mt-2 mb-5 justify-content-around text-center">

      <div class="col-lg-6 p-3 shadow-hover my-1 border rounded bg-img-3 pointer-hover" data-content="Favori Tarayıcı" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">{{$favBrowser}}</h2>
      </div>
      <div class="col-lg-6 p-3 shadow-hover my-1 border rounded bg-img-3 pointer-hover" data-content="Favori İşletim Sistemi" data-toggle="popover" data-placement="top">
        <h4 class="card-txt">{{$favOS}}<h2>
      </div>

    </div>
@include('widgets.charts')
  <div class="table-responsive">
    <table class="table table-striped table-bordered" id="table">
      <thead>
        <tr class="bg-dark text-light">
          <th class="font-weight-lighter" scope="col">#</th>
          <th class="font-weight-lighter" scope="col">Tarayıcı</th>
          <th class="font-weight-lighter" scope="col">İşletim Sistemi</th>
          <th class="font-weight-lighter" scope="col">Tarih</th>
        </tr>
      </thead>
      <tbody>
      @php
      $sort=1;
      @endphp
        @foreach($visitors as $visit)
        <tr>
          <td>{{$sort++}}</td>
          <td>{{$visit->browser}}</td>
          <td>{{$visit->os}}</td>
          <td>{{$visit->getDate()}}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
  </div>
</div>
</section>

@endsection
@section('js')
<script src="{{asset('js')}}/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$('#table').dataTable();
</script>

<script type="text/javascript" src="{{asset('js/demo/chart-area-demo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/demo/chart-pie-demo.js')}}"></script>
@endsection
