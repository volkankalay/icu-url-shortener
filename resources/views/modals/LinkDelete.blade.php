<div class="modal fade" id="delete-link-modal" tabindex="-1" role="dialog" aria-labelledby="delete-link-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="delete-link-title">Bağlantıyı devre dışı bırak</h2>
            </div>
            <div class="modal-body">
                <p><strong>/</strong><strong id="delete-link-code"></strong></p>
                <p class="mb-0">Kısa adres yönlendirmeyi durduracak. Ziyaret geçmişi saklanacak.</p>
            </div>
            <form class="modal-footer" action="{{ route('admin.links.delete') }}" method="post">
                @csrf
                <input name="link" type="hidden" id="delete-link-id">
                <button type="button" class="btn btn-light" data-dismiss="modal">İptal</button>
                <button type="submit" class="btn btn-primary">Devre dışı bırak</button>
            </form>
        </div>
    </div>
</div>
