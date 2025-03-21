<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Models\Absence;
use App\Models\User;
use Faker\Generator as Faker;
use App\Enums\AbsenceReason;

$factory->define(Absence::class, function (Faker $faker) {
    return [
        'external_id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'reason' => array_rand(AbsenceReason::values()),
        'start_at' => now(),
        'end_at' => now()->addDays(3),
        'user_id' => factory(User::class),
    ];
});
