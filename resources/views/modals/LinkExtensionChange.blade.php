<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="update-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="update-title">Kısa bağlantıyı düzenle</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{ route('url.update.post') }}" method="post">
                @csrf
                <div class="modal-body dashboard-form">
                    <x-auth.field name="shortold" label="Mevcut kısa kod" :default="$shortCode" readonly />
                    <x-auth.field name="shortnew" label="Yeni kısa kod" :default="$shortCode" minlength="4" maxlength="191" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="dashboard-button" data-dismiss="modal">İptal</button>
                    <button type="submit" class="dashboard-button dashboard-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
