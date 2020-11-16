<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SearchEnum extends Enum
{
    const LIMIT_HISTORIES = 8;

    const
        TYPE_PRIORITY_FIRST = 'first',
        TYPE_PRIORITY_SECOND = 'second',
        TYPE_PRIORITY_THIRD = 'third';

    const LIMIT_USER_SEARCH = 8;
    const LIMIT_SUGGEST = 2;
    const LIMIT_BRAND = 10;


    public static function priorities()
    {
        return [
            self::TYPE_PRIORITY_FIRST,
            self::TYPE_PRIORITY_SECOND,
            self::TYPE_PRIORITY_THIRD
        ];
    }
}
