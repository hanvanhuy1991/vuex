<?php

return [
    'fcm_server_key' => env('FCM_SERVER_KEY'),
    'ffmpeg' => env('SRC_FFMPEG', '/snap/bin/ffmpeg'),
    'aws_url' => env('AWS_URL', 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com'),
    'domain' => env('DOMAIN', 'comuni.vmodev.jp/'),
];
