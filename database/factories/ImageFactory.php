<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\ImageEnum;
use App\Models\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);
    $models = [
        ImageEnum::MODEL_COMMENT,
        ImageEnum::MODEL_POST,
        ImageEnum::MODEL_USER
    ];

    return [
        'user_id' => $userId,
        'imageable_id' => $faker->numberBetween(1, 100),
        'imageable_type' => $models[$faker->numberBetween(0, 2)],
        'image_path' => $faker->image(),
        'width' => $faker->numberBetween(1, 100),
        'height' => $faker->numberBetween(1, 100),
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
