<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const ADMIN = 'admin';
    const BUSINESS_ACCOUNT = 'business_account';
    const UNICER = 'unicer';
    const INFLUENCER = 'influencer';

    const
        FEMALE = 0,
        MALE = 1,
        OTHER = 2;
    const
        ACTIVE = 1,
        LOCKED = 2,
        TEMPORARY_LOCKED = 3;

    const RADIUS_LOCATION = 50;
    
    public static function genders()
    {
        return [
            'women' => self::FEMALE,
            'men' => self::MALE,
            'other' =>self::OTHER
        ];
    }
}
