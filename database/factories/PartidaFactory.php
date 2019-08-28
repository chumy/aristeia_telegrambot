<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Partida;
use App\User;
use Faker\Generator as Faker;

$factory->define(Partida::class, function (Faker $faker) {
    return [
        //
        //'user' => $faker->randomDigitNotNull,
        'fecha' => $faker->date,
        'user_id' => User::all()->random()->id,

    ];
});
