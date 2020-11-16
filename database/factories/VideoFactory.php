<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'user_id' => $userId,
        'videoable_id' => $faker->numberBetween(1, 100),
        'videoable_type' => 'App\Models\Post',
        'video_path' => $faker->image(),
        'width' => $faker->numberBetween(50, 100),
        'height' => $faker->numberBetween(50, 100),
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
