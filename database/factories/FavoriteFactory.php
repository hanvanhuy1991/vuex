<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\FavoriteEnum;
use App\Models\Bookmark;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'user_id' => $userId,
        'post_id' => $faker->numberBetween(1, 50),
        'is_favorited' => FavoriteEnum::IS_FAVORITE,
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
