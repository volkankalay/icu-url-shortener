<div class="modal fade" id="user-type-modal" tabindex="-1" role="dialog" aria-labelledby="user-type-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="{{ route('admin.users.rolechange') }}" method="post">
            @csrf
            <div class="modal-header"><h2 class="modal-title h5" id="user-type-title">Hesap türünü değiştir</h2></div>
            <div class="modal-body">
                <label for="type-username">Kullanıcı adı</label>
                <input name="username" id="type-username" class="dashboard-input mb-3" value="{{ $targetUser->username ?? '' }}" readonly>
                <label for="roleSelector">Hesap türü</label>
                <select name="user_type" class="dashboard-input" id="roleSelector" required>
                    @foreach (\App\Enums\UserType::cases() as $userType)
                        <option value="{{ $userType->value }}" @selected(($targetUser->user_type ?? null) === $userType)>{{ $userType->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="dashboard-button" data-dismiss="modal">İptal</button>
                <button type="submit" class="dashboard-button dashboard-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>
