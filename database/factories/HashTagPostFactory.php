<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HashTagPost;
use Faker\Generator as Faker;

$factory->define(HashTagPost::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 20);

    return [
        'post_id' => $faker->numberBetween(1, 30),
        'hash_tag_id' => $faker->numberBetween(1, 30),
        'created_by' => $userId,
        'updated_by' => $userId
    ];
});
