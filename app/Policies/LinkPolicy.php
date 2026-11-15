<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Link;
use App\Models\User;

class LinkPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->user_type === UserType::EDITOR;
    }

    public function view(User $user, Link $link): bool
    {
        return $link->owner_id === $user->id || $this->viewAny($user);
    }

    public function update(User $user, Link $link): bool
    {
        return $this->view($user, $link);
    }
}
