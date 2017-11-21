<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Db\Adapter\Pdo;
use Yarak\Migrations\Migration;

class CreateRolesPermissionsTable implements Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(Pdo $connection)
    {
        $connection->createTable(
            'roles_permissions',
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
                        'roles_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'permissions_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'roles_id'
                        )
                    ),
                    new Column(
                        'created_at',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'permissions_id',
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
                'references' => [
                    new Reference(
                        'roles_permissions_ibfk_1',
                        [
                            'referencedTable'   => 'roles',
                            'columns'           => ['roles_id'],
                            'referencedColumns' => ['id'],
                        ]
                    ),
                    new Reference(
                        'roles_permissions_ibfk_2',
                        [
                            'referencedTable'   => 'permissions',
                            'columns'           => ['permissions_id'],
                            'referencedColumns' => ['id'],
                        ]
                    ),
                ],
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
        $connection->dropForeignKey('roles_permissions', null, 'roles_permissions_ibfk_1');
        $connection->dropForeignKey('roles_permissions', null, 'roles_permissions_ibfk_2');
        $connection->dropTable('roles_permissions');
    }
}
