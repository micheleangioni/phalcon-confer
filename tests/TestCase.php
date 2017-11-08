<?php

namespace MicheleAngioni\PhalconConfer\Tests;

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class TestCase extends PhalconTestCase
{
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;


    public function setUp()
    {
        parent::setUp();

        // Load any additional services that might be required during testing
        $di = Di::getDefault();

        $di->set('modelsManager', function () {
            return new \Phalcon\Mvc\Model\Manager();
        });

        $di->set('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\Metadata\Memory();
        });

        $di->set('security', function () {
            $security = new \Phalcon\Security();

            return $security;
        }, true);

        $di->set('session', function () {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();

            return $session;
        });

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function () {
            return new \Phalcon\Db\Adapter\Pdo\Sqlite ([
                'dbname' => dirname(__DIR__) . '/tests/temp/db_sqlite_test.sqlite'
            ]);
        });

        $this->setDi($di);

        $this->_loaded = true;

        // Drop tables
        $this->dropTables($di->get('db'));

        // Migrate the DB
        $this->migrateTables($di->get('db'));

        // Seed the DB
        $this->seedDatabase($di->get('db'));
    }


    protected function migrateTables($connection)
    {
        $connection->createTable(
            'teams',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('username_UNIQUE', ['name'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );

        $connection->createTable(
            'users',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 60,
                            'after' => 'email'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('username_UNIQUE', ['email'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );

        $connection->createTable(
            'roles',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('name_UNIQUE', ['name'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );

        $connection->createTable(
            'permissions',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('name_UNIQUE', ['name'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );

        $connection->createTable(
            'roles_permissions',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'roles_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'permissions_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'roles_id'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'permissions_id'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );

        $connection->createTable(
            'users_roles',
            null,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'users_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'roles_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'users_id'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'roles_id'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );

        $connection->createTable(
            'teams_roles',
            null,
            [
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        )
                    ),
                    new Column(
                        'teams_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'users_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'teams_id'
                        )
                    ),
                    new Column(
                        'roles_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'teams_id'
                        )
                    ),
                    new Column(
                        'created_at',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'roles_id'
                        )
                    ),
                    new Column(
                        'updated_at',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'created_at'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY')
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ),
            ]
        );
    }

    protected function seedDatabase($connection)
    {
        // Create Users, Roles and Permissions records

        $user1 = static::$fm->create('MicheleAngioni\PhalconConfer\Tests\Users');
        $user2 = static::$fm->create('MicheleAngioni\PhalconConfer\Tests\Users');
        $role1 = static::$fm->create('MicheleAngioni\PhalconConfer\Models\Roles');
        $role2 = static::$fm->create('MicheleAngioni\PhalconConfer\Models\Roles');
        $permission1 = static::$fm->create('MicheleAngioni\PhalconConfer\Models\Permissions');
        $permission2 = static::$fm->create('MicheleAngioni\PhalconConfer\Models\Permissions');
        $team1 = static::$fm->create('MicheleAngioni\PhalconConfer\Tests\Teams');
        static::$fm->create('MicheleAngioni\PhalconConfer\Tests\Teams');

        // Attach Permissions to Roles

        $rolesPermissions = new \MicheleAngioni\PhalconConfer\Models\RolesPermissions();
        $rolesPermissions->save([
            'roles_id' => $role1->id,
            'permissions_id' => $permission1->id
        ]);

        $rolesPermissions = new \MicheleAngioni\PhalconConfer\Models\RolesPermissions();
        $rolesPermissions->save([
            'roles_id' => $role1->id,
            'permissions_id' => $permission2->id
        ]);

        $rolesPermissions = new \MicheleAngioni\PhalconConfer\Models\RolesPermissions();
        $rolesPermissions->save([
            'roles_id' => $role2->id,
            'permissions_id' => $permission2->id
        ]);

        // Attach Roles to Users

        $usersRole = new \MicheleAngioni\PhalconConfer\Models\UsersRoles();
        $usersRole->save([
            'users_id' => $user1->id,
            'roles_id' => $role1->id
        ]);

        $usersRole = new \MicheleAngioni\PhalconConfer\Models\UsersRoles();
        $usersRole->save([
            'users_id' => $user2->id,
            'roles_id' => $role2->id
        ]);

        // Attach Users to Teams

        $teamsRoles = new \MicheleAngioni\PhalconConfer\Models\TeamsRoles();
        $teamsRoles->save([
            'teams_id' => $team1->id,
            'users_id' => $user1->id,
            'roles_id' => $role1->id
        ]);
    }

    protected function dropTables($connection)
    {
        $connection->dropTable('teams');
        $connection->dropTable('users');
        $connection->dropTable('roles');
        $connection->dropTable('permissions');
        $connection->dropTable('roles_permissions');
        $connection->dropTable('users_roles');
        $connection->dropTable('teams_roles');
    }

    protected function tearDown()
    {
        $di = $this->getDI();
        $connection = $di->get('db');

        $di->get('modelsMetadata')->reset();

        $this->dropTables($connection);

        parent::tearDown();
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}

class Users extends \MicheleAngioni\PhalconConfer\Models\AbstractConferModel
{
    use \MicheleAngioni\PhalconConfer\ConferTrait;

    protected $id;

    protected $email;

    protected $password;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return true;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return true;
    }

}

class Teams extends \MicheleAngioni\PhalconConfer\Models\AbstractConferTeamModel
{
    use \MicheleAngioni\PhalconConfer\ConferTeamTrait;

    protected $id;

    protected $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}
