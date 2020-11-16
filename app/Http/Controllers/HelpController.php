<?php

namespace App\Http\Controllers;

use App\Http\Requests\HelpRequest;
use App\Services\HelpService;

class HelpController extends BaseController
{
    protected $helpService;

    public function __construct(HelpService $helpService)
    {
        parent::__construct();

        $this->helpService = $helpService;
    }

    /**
     * Store Help
     *
     * @param HelpRequest $helpRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(HelpRequest $helpRequest)
    {
        $data = $helpRequest->only(['email', 'phone', 'description']);

        return $this->responseSuccess($this->helpService->store($data));
    }
}
