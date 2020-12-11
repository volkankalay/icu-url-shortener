<!--
MODAL GIVE ADMIN RIGHTS
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="roleChangeModal" role="dialog" aria-labelledby="roleChangeModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="card-header text-right"data-dismiss="modal">
        <i class="fa fa-times"></i>
      </div>
      <div class="card alert">
        <h4 class="text-primary p-3 text-center">

          <form action="{{route('admin.users.rolechange')}}" method="post">
              @csrf
            <input type="text" id="roleChangeId" hidden/>
            <div class="form-group">
              <label>Kullanıcı Adı</label>
              <input name="username" type="text" class="fonm-input form-control" id="roleChangeUn" readonly/>
              <span class="badge badge-primary" id="roleChangeRol">&nbsp;</span>
            </div>

            <div class="form-group">
              <label>Yeni Yetki</label>
              <select name="yetki" class="custom-select" id="roleSelector" required>
                <option value="1">Yönetici</option>
                <option value="2">Editör</option>
                <option value="3" selected>Üye</option>
              </select>
            </div>
            <small class="h6 text-danger my-3">Yönetici yetkisi verirken dikkat ediniz!<br>Kullanıcı sizinle aynı yetkiye sahip olacaktır!</small>
            <div class="text-center">
              <button type="button" class="btn btn-light border" data-dismiss="modal">İptal</button>
              <button type="submit" class="btn btn-primary">Uygula</button>
            </div>
          </form>

        </h4>
      </div>
    </div>
  </div>
</div>
