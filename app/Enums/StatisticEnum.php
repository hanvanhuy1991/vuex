<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatisticEnum extends Enum
{
    const AGE_MAX = 65;
    const AGE_MIN = 12;

    const
        TODAY = 0,
        WEEKLY = 7,
        MONTHLY = 30;

    const
        TYPE_ALL = 1,
        TYPE_LIKE = 2,
        TYPE_COMMENT = 3,
        TYPE_SHARE = 4,
        TYPE_VIEW = 5;

    public static function types()
    {
        return [
            self::TYPE_ALL,
            self::TYPE_LIKE,
            self::TYPE_COMMENT,
            self::TYPE_SHARE,
            self::TYPE_VIEW
        ];
    }

    public static function timePeriods()
    {
        return [
            self::TODAY,
            self::WEEKLY,
            self::MONTHLY
        ];
    }

    public static function rangeAges()
    {
        return [
            [
                'min' => 13,
                'max' => 17
            ],
            [
                'min' => 18,
                'max' => 24
            ],
            [
                'min' => 25,
                'max' => 44
            ],
            [
                'min' => 45,
                'max' => 54
            ],
            [
                'min' => 55,
                'max' => 64
            ]
        ];
    }
}
