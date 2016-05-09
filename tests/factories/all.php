<?php

namespace Learnph\Tests;

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MicheleAngioni\PhalconAuth\Tests\Users')->setDefinitions([
    'confirmation_code'  => Faker::md5(),
    //'confirmed'          => Faker::boolean(),
    'banned'    => 0,
    'email'    => Faker::email(),
    'password' => Faker::password(),
    'remember_token' => Faker::regexify('[A-Z0-9._%+-]{20,40}')
]);
