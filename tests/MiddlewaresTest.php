<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\FactoryMuffin;
use MicheleAngioni\PhalconConfer\Models\Roles;

class MiddlewaresTest extends TestCase
{
    protected static $fm;

    public static function setupBeforeClass()
    {
        // create a new factory muffin instance
        static::$fm = new FactoryMuffin();

        // you can customize the save/delete methods
        // new FactoryMuffin(new ModelStore('save', 'delete'));

        // load your model definitions
        static::$fm->loadFactories(__DIR__ . '/factories');

        parent::setUpBeforeClass();
    }

    public function testRolesMiddleware()
    {
        $di = $this->getDI();

        $users = new Users();
        $user = $users::findFirst();

        $this->setAuthMock($di, $user);

        $middleware = new \MicheleAngioni\PhalconConfer\Middlewares\RolesMiddleware($user->getRoles()->getFirst()->getName());

        $this->assertTrue($middleware->check());
    }

    public function testRolesMiddlewareFailingNoRole()
    {
        $di = $this->getDI();

        $users = new Users();
        $user = $users::findFirst();

        $this->setAuthMock($di, $user);

        $middleware = new \MicheleAngioni\PhalconConfer\Middlewares\RolesMiddleware('Hacker');

        $this->assertFalse($middleware->check());
    }

    protected function setAuthMock(\Phalcon\DiInterface $di, $user = null)
    {
        $di->set('auth', function () use ($user) {
            return new AuthMock($user);
        });
    }
}

class AuthMock
{
    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user;
    }

    public function getAuth()
    {
        return $this->user;
    }
}
