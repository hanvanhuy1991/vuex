<?php

namespace App\Repositories\Reason;

use App\Repositories\BaseRepositoryInterface;

/**
 * Class LabelReasonRepositoryInterface
 *
 * @package App\Repositories\Reason
 */
interface LabelReasonRepositoryInterface extends BaseRepositoryInterface
{
    public function getByType($type);
}
