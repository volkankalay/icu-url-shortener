
<!--
MODAL PASSWORD CHANGE
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="deleteModal" role="dialog" aria-labelledby="passwordUpdateModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="text-right">
        <button type="button" class="btn btn-light border" data-dismiss="modal"><i class="fa fa-times"></i></button>
      </div>
      <div class="card alert">
        <form action="{{route('profile.delete')}}" method="post">
          @csrf
          <input name="username" type="text" value="{{$profile->username}}" class="form-control border-none input-flat bg-ash" hidden>

          <div class="basic-form m-t-20">
            <div class="form-group">
                <label class="font-weight-bold renk-blue">Mevcut Şifre:</label>
                <input name="password_old" type="password" class="form-control border-none input-flat bg-ash" required>
            </div>
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <input type="checkbox" id="cb1" required>
              </div>
            </div>
            <label class="form-control" for="cb1">Hesabımı kapatmak istiyorum.</label>
          </div>

            <div class="text-center">
              <button type="submit" class="btn btn-sm btn-danger border">Hesabı Kapat</button>
              <button type="button" class="btn btn-lg btn-success border" data-dismiss="modal">İptal Et</button>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
