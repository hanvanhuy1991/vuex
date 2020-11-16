<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HelpEnum extends Enum
{
    const
        STATUS_REJECT = 0,
        STATUS_APPROVE = 1,
        STATUS_PENDING = 2;
}
