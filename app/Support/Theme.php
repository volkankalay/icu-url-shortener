<?php

namespace App\Support;

final class Theme
{
    public const array COLORS = [
        'light' => [
            'background' => '#f2f3f4',
            'surface'    => '#e6e8ea',
            'raised'     => '#d4d8dc',
            'border'     => '#747a80',
            'muted'      => '#4d5257',
            'text'       => '#202428',
            'success-bg' => '#d3f3df',
            'success-fg' => '#125338',
            'info-fg'    => '#365f79',
            'warning-fg' => '#75551e',
            'error-fg'   => '#853e4c',
        ],
        'dark' => [
            'background' => '#131926',
            'surface'    => '#1b2230',
            'raised'     => '#2b3444',
            'border'     => '#69768b',
            'muted'      => '#aeb8ca',
            'text'       => '#e7ebf2',
            'success-bg' => '#194b37',
            'success-fg' => '#bcf4ce',
            'info-fg'    => '#bfd1ee',
            'warning-fg' => '#e9d3a5',
            'error-fg'   => '#eabdc5',
        ],
    ];
}
