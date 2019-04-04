<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $date = $faker->date();
    $user_id = rand(1, 50);
    DB::table('users')->where('id', $user_id)->increment('postsQty');

    return [
        'titre' => $faker->sentence,
        'contenu' => $faker->text,
        'user_id' => $user_id,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
