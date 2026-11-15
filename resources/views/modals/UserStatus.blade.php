<div class="modal fade" id="user-status-modal" tabindex="-1" role="dialog" aria-labelledby="user-status-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="{{ route($suspended ? 'admin.users.unsuspend' : 'admin.users.suspend') }}" method="post">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title h5" id="user-status-title">{{ $suspended ? 'Hesabı aktif et' : 'Hesabı dondur' }}</h2>
            </div>
            <div class="modal-body">
                <strong id="status-username"></strong>
                <input name="user" type="hidden" id="status-user-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="dashboard-button" data-dismiss="modal">İptal</button>
                <button type="submit" class="dashboard-button dashboard-primary">{{ $suspended ? 'Aktif et' : 'Dondur' }}</button>
            </div>
        </form>
    </div>
</div>
