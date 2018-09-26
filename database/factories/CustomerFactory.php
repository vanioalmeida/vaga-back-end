<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        // ddd+number
        'telephone' => $faker->numberBetween(11, 99) + $faker->numberBetween(900000000, 999999999),
        // it'll be generated 3 customers
        'user_id' => $faker->numberBetween(1, 3)
    ];
});
