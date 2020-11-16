<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HashTag;
use Faker\Generator as Faker;

$factory->define(HashTag::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 20);

    return [
        'topic_id' => $faker->numberBetween(1, 10),
        'hash_tag_name' => $faker->text(10),
        'point' => $faker->numberBetween(1, 100),
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
