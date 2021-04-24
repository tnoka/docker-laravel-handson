<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'id' => Str::random(12),
        'title' => $faker->title,
        'author' => $faker->name,
        'recommend' => "★★★★★",
        'text' => $faker->text,
        'product_image' => Str::random(12) . '.jpg',
        'user_id' => function(){return factory(App\User::class)->create()->id;},
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
