<?php

namespace Learnph\Tests;

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MicheleAngioni\PhalconConfer\Tests\Users')->setDefinitions([
    'email' => Faker::email(),
    'password' => Faker::password()
]);

$fm->define('MicheleAngioni\PhalconConfer\Models\Roles')->setDefinitions([
    'name' => Faker::username()
]);

$fm->define('MicheleAngioni\PhalconConfer\Models\Permissions')->setDefinitions([
    'name' => Faker::username()
]);

$fm->define('MicheleAngioni\PhalconConfer\Tests\Teams')->setDefinitions([
    'name' => Faker::city()
]);
