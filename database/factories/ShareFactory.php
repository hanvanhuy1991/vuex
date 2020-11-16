<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\ShareEnum;
use App\Models\Share;
use Faker\Generator as Faker;

$factory->define(Share::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'user_id' => $userId,
        'post_id' => $faker->numberBetween(1, 50),
        'share_date' => $faker->date('Y-m-d'),
        'type' => ShareEnum::STATUS_ACTIVE,
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
