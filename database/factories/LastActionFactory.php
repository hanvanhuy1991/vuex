<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LastAction;
use Faker\Generator as Faker;

$factory->define(LastAction::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'user_id' => $userId,
        'post_id' => $faker->numberBetween(1, 50),
        'type' => $faker->numberBetween(1, 5),
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
