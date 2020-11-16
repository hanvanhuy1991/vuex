<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\CommentEnum;
use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $userId = $faker->numberBetween(1, 10);

    return [
        'parent_id' => null,
        'user_id' => $userId,
        'commentable_id' => $faker->numberBetween(1, 50),
        'commentable_type' => CommentEnum::MODEL_POST,
        'content' => $faker->text(50),
        'created_by' => $userId,
        'updated_by' => $userId,
    ];
});
