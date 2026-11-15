<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="profile-delete-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="{{ route('profile.delete') }}" method="post">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title h5" id="profile-delete-title">Hesabı kapat</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body profile-delete-form">
                <x-auth.field name="current_password" field-id="delete-current-password" label="Mevcut şifre" type="password" autocomplete="current-password" error-bag="accountDeletion" />
                <label class="auth-remember">
                    <input type="checkbox" name="confirm_delete" value="1" @checked(old('confirm_delete')) required>
                    Hesabımı kapatmak istiyorum.
                </label>
                @error('confirm_delete', 'accountDeletion')
                    <p class="auth-field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="dashboard-button" data-dismiss="modal">İptal</button>
                <button type="submit" class="dashboard-button profile-close-button">Hesabı kapat</button>
            </div>
        </form>
    </div>
</div>
