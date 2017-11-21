<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Db\Adapter\Pdo;
use Yarak\Migrations\Migration;

class CreateUsersRolesTable implements Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(Pdo $connection)
    {
        $connection->createTable(
            'users_roles',
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
                        'users_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'roles_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'users_id'
                        )
                    ),
                    new Column(
                        'created_at',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'roles_id',
                            'notNull' => true,
                            'default' => 'CURRENT_TIMESTAMP'
                        )
                    ),
                    new Column(
                        'updated_at',
                        array(
                            'type'    => Column::TYPE_DATETIME,
                            'after' => 'created_at'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY')
                )
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(Pdo $connection)
    {
        $connection->dropTable('users_roles');
    }
}
