<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Remove 'data' wrap in response by setting static::$wrap = null
     *
     * @see JsonResource@withoutWrapping
     *
     */
    public static $wrap = null;

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public static function apiPaginate($query, Request $request)
    {
        $pageSize = config('api.pagination.per_page');

        if (($pageSizeInput = (int)$request->input('page_size')) > 0) {
            $pageSize = min($pageSizeInput, config('api.pagination.max_per_page'));
        }

        return static::collection($query->paginate($pageSize)->appends($request->query()))
            ->response()
            ->getData();
    }

    /**
     * Check business account
     *
     * @param [model] $user
     *
     * @return bool
     */
    public function checkBusinessAccount($user)
    {
        return in_array(UserRole::BUSINESS_ACCOUNT, $user->roles->pluck('name')->toArray());
    }
}
