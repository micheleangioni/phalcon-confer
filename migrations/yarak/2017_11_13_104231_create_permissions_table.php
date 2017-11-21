<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Db\Adapter\Pdo;
use Yarak\Migrations\Migration;

class CreatePermissionsTable implements Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(Pdo $connection)
    {
        $connection->createTable(
            'permissions',
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
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'created_at',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'size' => 1,
                            'after' => 'name',
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
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('name_UNIQUE', array('name'), 'UNIQUE')
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
        $connection->dropTable('permissions');
    }
}
