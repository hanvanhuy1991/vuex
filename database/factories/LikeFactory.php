<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\LikeEnum;
use App\Models\Like;
use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);
    $models = [
        LikeEnum::MODEL_POST,
        LikeEnum::MODEL_COMMENT
    ];
    return [
        'user_id' => $userId,
        'likeable_type' => $models[$faker->numberBetween(0, 1)],
        'likeable_id' => $faker->numberBetween(1, 30),
        'is_liked' => LikeEnum::STATUS_LIKE,
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
