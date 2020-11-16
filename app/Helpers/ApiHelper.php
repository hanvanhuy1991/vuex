<?php

function api_response($data, $responseCode, $statusCode)
{
    return response()->json([
        'status' => $responseCode,
        'data' => $data,
    ], $statusCode);
}

function lowercaseStatus($status)
{
    return strtolower(str_replace('STATUS_', '', $status));
}
