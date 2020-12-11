
<!--
MODAL FORCE DELETE
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="forceDeleteModal" role="dialog" aria-labelledby="forceDeleteModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="card-header text-right"data-dismiss="modal">
        <i class="fa fa-times"></i>
      </div>
      <div class="card alert">
        <h4 class="text-danger p-1 text-center">
          {{$settings->site}}/<span id="yazdirun"></span>
          <br>
          <br><br>Kısaltılmış adres ve ziyaretçi bilgileri silinecektir.
          <br><br>Bu işlemin geri dönüşü olmayacaktır!
          <br><br>Onaylıyor musunuz?
        </h4>
      </div>
      <div class="card-footer">
        <form action="{{route('admin.links.force')}}" method="post">
          @csrf
          <input name="link" type="number" id="forceid" hidden>
          <div class="text-center">
            <button type="button" class="btn btn-light border" data-dismiss="modal">İptal</button>
            <button type="submit" class="btn btn-danger btn-sm">Adresi Sil</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
