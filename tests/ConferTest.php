<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\FactoryMuffin;
use MicheleAngioni\PhalconConfer\Confer;
use MicheleAngioni\PhalconConfer\Models\Permissions;
use MicheleAngioni\PhalconConfer\Models\Roles;
use MicheleAngioni\PhalconConfer\PermissionService;
use MicheleAngioni\PhalconConfer\RoleService;

class ConferTest extends TestCase
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

    public function testGetRoles()
    {
        $number = 5;

        $confer = $this->createConferInstance();
        $oldNumber = count($confer->getRoles());

        static::$fm->seed($number, Roles::class);

        $roles = $confer->getRoles();

        $this->assertEquals($number + $oldNumber, count($roles));
        $this->assertInstanceOf(Roles::class, $roles[0]);
    }

    public function testGetRole()
    {
        $name = 'My Role';

        static::$fm->create(Roles::class, ['name' => $name]);

        $confer = $this->createConferInstance();
        $role = $confer->getRole($name);

        $this->assertInstanceOf(Roles::class, $role);
        $this->assertEquals($name, $role->getName());
    }

    public function testCreateRole()
    {
        $name = 'My Role';

        $confer = $this->createConferInstance();
        $role = $confer->createRole($name);

        $this->assertInstanceOf(Roles::class, $role);
        $this->assertEquals($name, $role->getName());
    }

    public function testGetPermissions()
    {
        $number = 5;

        $confer = $this->createConferInstance();
        $oldNumber = count($confer->getPermissions());

        static::$fm->seed($number, Permissions::class);

        $permissions = $confer->getPermissions();

        $this->assertEquals($number + $oldNumber, count($permissions));
        $this->assertInstanceOf(Permissions::class, $permissions[0]);
    }

    public function testGetPermission()
    {
        $name = 'My Permission';

        static::$fm->create(Permissions::class, ['name' => $name]);

        $confer = $this->createConferInstance();
        $permission = $confer->getPermission($name);

        $this->assertInstanceOf(Permissions::class, $permission);
        $this->assertEquals($name, $permission->getName());
    }

    public function testCreatePermission()
    {
        $name = 'My Permission';

        $confer = $this->createConferInstance();
        $permission = $confer->createPermission($name);

        $this->assertInstanceOf(Permissions::class, $permission);
        $this->assertEquals($name, $permission->getName());
    }

    protected function createConferInstance(): Confer
    {
        return new Confer(
            new RoleService(new Roles()),
            new PermissionService(new Permissions())
        );
    }
}
