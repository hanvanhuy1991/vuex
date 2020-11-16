<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class BaseController extends Controller
{
    public function __construct()
    {
    }

    public function responseSuccess($data, $responseCode = Response::HTTP_OK, $statusCode = Response::HTTP_OK)
    {
        return api_response($data, $responseCode, $statusCode);
    }

    public function responseError($data, $responseCode, $statusCode)
    {
        return api_response($data, $responseCode, $statusCode);
    }
}
