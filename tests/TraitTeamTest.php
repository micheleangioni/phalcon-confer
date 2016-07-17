<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use League\FactoryMuffin\FactoryMuffin;
use MicheleAngioni\PhalconConfer\Models\Roles;

class TraitTeamTest extends TestCase
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
        $role = $roles::findFirst(["id = 1"]);

        $users = new Users();
        $user = $users::findFirst(["id = 1"]);

        $teams = new Teams();
        $team = $teams::findFirst(["id = 2"]);

        $startingUserTeamsNumber = count($team->getRolesPivot());

        $team->attachUserRole($user->getId(), $role);
        $this->assertEquals($startingUserTeamsNumber + 1, count($team->getRolesPivot()));

        $team->detachUserRole($user->getId());
        $team = $teams::findFirst(["id = 2"]);
        $this->assertEquals($startingUserTeamsNumber, count($team->getRolesPivot()));
    }

    public function testTeamUserHasRole()
    {
        $teams = new Teams();
        $team = $teams::findFirst(["id = 1"]);

        $users = new Users();
        $user = $users::findFirst(["id = 1"]);

        $roles = new Roles();
        $role = $roles::findFirst(["id = 1"]);

        $this->assertTrue($team->userHasRole($user->getId(), $role->getName()));
        $this->assertFalse($team->userHasRole($user->getId(), 'Mod'));
    }

    public function testUserCan()
    {
        $teams = new Teams();
        $team = $teams::findFirst(["id = 1"]);

        $users = new Users();
        $user = $users::findFirst(["id = 1"]);

        $roles = new Roles();
        $role = $roles::findFirst(["id = 1"]);
        $permission = $role->getPermissions()->getFirst();

        $this->assertTrue($team->userCan($user->getId(), $permission->getName()));
        $this->assertFalse($team->userCan($user->getId(), 'format_the_hd'));
    }
}
