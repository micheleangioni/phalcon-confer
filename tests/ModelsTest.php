<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\FactoryMuffin;

class ModelsTest extends TestCase
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

    public function testUserRelationships()
    {
        $users = new Users();
        $user = $users::findFirst(["id = 1"]);

        $this->assertEquals(1, count($user->getRoles()));
    }

    public function testRolesRelationships()
    {
        $roles = new \MicheleAngioni\PhalconConfer\Models\Roles();
        $role = $roles::findFirst();
        $this->assertEquals(2, count($role->getPermissions()));
    }

    public function testDelete()
    {
        $rolesPermissions = new \MicheleAngioni\PhalconConfer\Models\RolesPermissions();
        $rolesPermissionsNumberBefore = count($rolesPermissions->find());

        $userRoles = new \MicheleAngioni\PhalconConfer\Models\UsersRoles();
        $userRolesNumberBefore = count($userRoles->find());

        $roles = new \MicheleAngioni\PhalconConfer\Models\Roles();
        $role = $roles::findFirst();
        $role->delete();

        $rolesPermissionsNumberAfter = count($rolesPermissions->find());
        $userRolesNumberAfter = count($userRoles->find());

        $this->assertLessThan($rolesPermissionsNumberBefore, $rolesPermissionsNumberAfter);
        $this->assertLessThan($userRolesNumberBefore, $userRolesNumberAfter);
    }

    public function testAttachAndDetachPermission()
    {
        $roles = new \MicheleAngioni\PhalconConfer\Models\Roles();
        $role = $roles::findFirst(["id = 2"]);

        $basePermissionsNumber = count($role->getPermissions());

        $permissions = new \MicheleAngioni\PhalconConfer\Models\Permissions();
        $permission = $permissions::findFirst(["id = 1"]);

        $role->attachPermission($permission);
        $role = $roles::findFirst(["id = 2"]);
        $this->assertEquals($basePermissionsNumber + 1, count($role->getPermissions()));

        $role->detachPermission($permission);
        $role = $roles::findFirst(["id = 2"]);
        $this->assertEquals($basePermissionsNumber, count($role->getPermissions()));
    }

}
