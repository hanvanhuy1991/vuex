<?php

namespace App\Services;

use App\Enums\HelpEnum;
use Illuminate\Support\Facades\Auth;

class HelpService
{
    /**
     * Store Help
     *
     * @param $data
     *
     * @return mixed
     */
    public function store($data)
    {
        return Auth::user()->helpCenters()->create([
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'content' => $data['description'],
            'status' => HelpEnum::STATUS_PENDING
        ]);
    }
}
