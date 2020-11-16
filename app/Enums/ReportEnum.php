<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReportEnum extends Enum
{
    const
       REPORT_USER = 1,
       REPORT_POST = 2,
       REPORT_COMMENT = 2;

    const
        STATUS_REJECT = 0,
        STATUS_PENDING = 1,
        STATUS_APPROVE = 2;

    const
        MODEL_USER = 'App\Models\User',
        MODEL_POST = 'App\Models\Post',
        MODEL_COMMENT = 'App\Models\Comment';
}
