<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\FactoryMuffin;
use MicheleAngioni\PhalconConfer\Models\Roles;

class TraitTest extends TestCase
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

    public function testAttachAndDetach()
    {
        $roles = new \MicheleAngioni\PhalconConfer\Models\Roles();
        $role = $roles::findFirst(["id = 2"]);

        $users = new Users();
        $user = $users::findFirst(["id = 1"]);

        $baseRolesNumber = count($user->getRoles());

        $user->attachRole($role);
        $user = $users::findFirst(["id = 1"]);
        $this->assertEquals($baseRolesNumber + 1, count($user->getRoles()));

        $user->detachRole($role);
        $user = $users::findFirst(["id = 1"]);
        $this->assertEquals($baseRolesNumber, count($user->getRoles()));
    }

    public function testHasRole()
    {
        $users = new Users();
        $user = $users::findFirst();

        $role = $user->getRoles()->getFirst();

        $this->assertTrue($user->hasRole($role->getName()));
        $this->assertFalse($user->hasRole('Mod'));
    }

    public function testCan()
    {
        $users = new Users();
        $user = $users::findFirst();

        $role = $user->getRoles()->getFirst();
        $permission = $role->getPermissions()->getFirst();

        $this->assertTrue($user->can($permission->getName()));
        $this->assertFalse($user->can('format_the_hd'));
    }
}
