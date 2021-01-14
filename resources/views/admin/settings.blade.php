@extends('dashboard')
@section('css')
<link href="{{asset('css')}}/jquery.dataTables.css" rel="stylesheet">
@endsection
@section('dashboardcontent')
<section>
  <div class="card border-0 p-4">
    <span class="text-center h2 text-primary">Ayarlar</span>
      <div class="dropdown-divider my-2"></div>

      <div class=" p-2">
        <form action="{{route('admin.settings.update')}}" method="post" enctype="multipart/form-data">
@csrf

<div class="row">
  <div class="col-lg-6">

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Site Adresi</label>
        <div class="col-lg-8">
          <input name="site" type="text" class="form-control" value="{{$settings->site}}">
          <small class="text-muted">Kullanılan alan adını giriniz.</small>
        </div>
      </div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Site Başlığı</label>
        <div class="col-lg-8">
          <input name="title" type="text" class="form-control" value="{{$settings->title}}">
          <small class="text-muted">Sitenin adını giriniz.</small>
        </div>
      </div>

      <div class="dropdown-divider my-4"></div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Mevcut Logo</label>
        <div class="col-lg-8">
          <img src="{{$settings->logo}}" class="img-responsive" width="48px">
        </div>
      </div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Yeni Logo</label>
          <div class="col-lg-8">
            <input name="logo" type="file" class="custom-file-input" id="logo" accept="image/jpeg,image/gif,image/x-png">
            <label class="custom-file-label" for="logo">Dosya Seç</label>
            <small class="text-muted">Sitenin logosunu belirleyin. Değiştirmek istemiyorsanız boş bırakın.</small>
          </div>
      </div>

      <div class="dropdown-divider my-4"></div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Mevcut Favicon</label>
        <div class="col-lg-8">
          <img src="{{$settings->favicon}}" class="img-responsive" width="48px">
        </div>
      </div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Yeni Favicon</label>
          <div class="col-lg-8">
            <input name="favicon" type="file" class="custom-file-input" id="favicon" accept="image/jpeg,image/x-png">
            <label class="custom-file-label" for="favicon">Dosya Seç</label>
            <small class="text-muted">Sitenin logosunu belirleyin. Değiştirmek istemiyorsanız boş bırakın.</small>
          </div>
      </div>

      <div class="dropdown-divider my-4"></div>

      <div class="form-group row">
        <label  class="col-lg-4 col-form-label">Kayıt Ol</label>
        <div class="col-lg-8">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="register" id="swReg" @if($settings->register==1) checked @endif>
            <label class="custom-control-label" for="swReg">Kullanıcı Kayıt Olabilsin.</label>
          </div>
          <small class="text-muted">Siteye yeni üyelerin kayıt olabilmesi için bu ayarı aktif hale getiriniz.</small>
        </div>
      </div>

    </div>

    <div class="col-lg-6">


        <div class="form-group row">
          <label  class="col-lg-3 col-form-label">Anahtar Kelimeler</label>
          <div class="col-lg-9">
            <input name="keywords" type="text" class="form-control" value="{{$settings->keywords}}">
            <small class="text-muted">Sitenin anahtar kelimelerini(keywords) giriniz. Her birini virgül ile ayırınız.</small>
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-lg-3 col-form-label">Site Açıklaması</label>
          <div class="col-lg-9">
            <textarea name="description" rows="5" class="form-control">{{$settings->description}}</textarea>
            <small class="text-muted">Sitenin açıklama(description) bilgisini giriniz.</small>
          </div>
        </div>


        <div class="form-group row">
          <label  class="col-lg-3 col-form-label">Header Kodu</label>
          <div class="col-lg-9">
            <textarea name="header_code" rows="5" class="form-control">{{$settings->header_code}}</textarea>
            <small class="text-muted">Sitenin header kısmına eklenecek kod.</small>
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-lg-3 col-form-label">Footer Kodu</label>
          <div class="col-lg-9">
            <textarea name="footer_code" rows="5" class="form-control">{{$settings->footer_code}}</textarea>
            <small class="text-muted">Sitenin footer kısmına eklenecek kod.</small>
          </div>
        </div>



        </div>


      </div>

      <div class="dropdown-divider my-2"></div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">Güncelle</button>
      </div>




      </form>
    </div>

  </div>
</section>
@endsection
@section('js')
@endsection
