<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\Faker\Facade as Faker;
use MicheleAngioni\PhalconConfer\Models\Permissions;
use MicheleAngioni\PhalconConfer\Models\Roles;

$fm->define(Users::class)->setDefinitions([
    'email' => Faker::email(),
    'password' => Faker::password()
]);

$fm->define(Roles::class)->setDefinitions([
    'name' => Faker::username()
]);

$fm->define(Permissions::class)->setDefinitions([
    'name' => Faker::username()
]);

$fm->define(Teams::class)->setDefinitions([
    'name' => Faker::city()
]);
