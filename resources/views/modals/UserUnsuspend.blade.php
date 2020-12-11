
<!--
MODAL UNSUSPEND
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="suspendModal" role="dialog" aria-labelledby="suspendModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="card-header text-right"data-dismiss="modal">
        <i class="fa fa-times"></i>
      </div>
      <div class="card alert">
        <h4 class="text-success p-3 text-center">
          <span id="yazdirid"></span>
          <br>
          Üyelik Aktif Edilsin?
        </h4>
      </div>
      <div class="card-footer">
        <form action="{{route('admin.users.unsuspend')}}" method="post">
          @csrf
          <input name="user" type="number" id="suspendid" hidden>
          <div class="text-center">
            <button type="button" class="btn btn-light border" data-dismiss="modal">İptal</button>
            <button type="submit" class="btn btn-success">Üyeliği Aktif Et</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
