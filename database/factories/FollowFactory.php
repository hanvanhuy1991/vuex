<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\FollowEnum;
use App\Models\Follow;
use Faker\Generator as Faker;

$factory->define(Follow::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'follower_id' => $userId,
        'is_followed_id' => $faker->numberBetween(2, 10),
        'status' => FollowEnum::STATUS_ACTIVE,
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
