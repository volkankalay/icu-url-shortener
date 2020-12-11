
<!--
MODAL
-->
<div class="modal fade bd-example-modal-md" tabindex="-1" id="updateModal" role="dialog" aria-labelledby="terminModal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="text-right">
        <button type="button" class="btn btn-light border" data-dismiss="modal"><i class="fa fa-times"></i></button>
      </div>
      <div class="card alert">
        <form action="{{route('url.update.post')}}" method="post">
          @csrf
          <div class="basic-form m-t-20">
            <div class="form-group">
                <label class="font-weight-bold renk-blue">Mevcut Adres:</label>
                <input name="shortold" type="text" id="shortold" class="form-control border-none input-flat bg-ash" readonly>
            </div>
          </div>

            <div class="basic-form m-t-20">
              <div class="form-group">
                  <label class="font-weight-bold renk-blue">Yeni Adres:<span class="font-weight-bold" id="ourlinkhere"></span></label>
                  <input name="shortnew" type="text" id="shortnew" class="form-control border-none input-flat bg-ash" required>
              </div>
            </div>

            <div class="text-center">
              <button type="button" class="btn btn-light border" data-dismiss="modal">İptal</button>
              <button type="submit" class="btn btn-primary border">Güncelle</button>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
