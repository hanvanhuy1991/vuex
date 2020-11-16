<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LikeEnum extends Enum
{
    const
        STATUS_LIKE = 1,
        STATUS_DISLIKE = 0;

    const
        MODEL_POST = 'App\Models\Post',
        MODEL_COMMENT = 'App\Models\Comment';
}
