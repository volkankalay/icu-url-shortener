
<!--
MODAL PASSWORD CHANGE
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="passwordUpdateModal" role="dialog" aria-labelledby="passwordUpdateModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="text-right">
        <button type="button" class="btn btn-light border" data-dismiss="modal"><i class="fa fa-times"></i></button>
      </div>
      <div class="card alert">
        <form action="{{route('profile.pass')}}" method="post">
          @csrf
          <input name="username" type="text" value="{{$profile->username}}" class="form-control border-none input-flat bg-ash" hidden>

          <div class="basic-form m-t-20">
            <div class="form-group">
                <label class="font-weight-bold renk-blue">Mevcut Şifre:</label>
                <input name="password_old" type="password" class="form-control border-none input-flat bg-ash" required>
            </div>
          </div>

          <div class="basic-form m-t-20">
            <div class="form-group">
                <label class="font-weight-bold renk-blue">Yeni Şifre:</label>
                <input name="password" type="password" class="form-control border-none input-flat bg-ash" required>
            </div>
          </div>

          <div class="basic-form m-t-20">
            <div class="form-group">
                <label class="font-weight-bold renk-blue">Yen Şifre:(Tekrar)</label>
                <input name="password_confirmation" type="password" class="form-control border-none input-flat bg-ash" required>
            </div>
          </div>

            <div class="text-center">
              <button type="button" class="btn btn-lg btn-light border" data-dismiss="modal">İptal</button>
              <button type="submit" class="btn btn-lg btn-primary border">Güncelle</button>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
