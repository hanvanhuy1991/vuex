<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ImageEnum extends Enum
{
    const
        MODEL_POST = 'App\Models\Post',
        MODEL_COMMENT = 'App\Models\Comment',
        MODEL_USER = 'App\Models\User';
}
