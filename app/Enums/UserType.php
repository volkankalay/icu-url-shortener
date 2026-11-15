<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case REGULAR = 'regular';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN   => 'Yönetici',
            self::EDITOR  => 'Editör',
            self::REGULAR => 'Üye',
        };
    }
}
