<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use App\Models\Location;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Location::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'image' => $faker->word,
        'workspace_id' => $faker->unique()->numberBetween($min = 0, $max = 1),
        'color' => $faker->word,
    ];
});
