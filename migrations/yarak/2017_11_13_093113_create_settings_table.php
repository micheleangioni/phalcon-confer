<?php

use Phalcon\Db\Index;
use Phalcon\Db\Column;
use Phalcon\Db\Reference;
use Phalcon\Db\Adapter\Pdo;
use Yarak\Migrations\Migration;

class CreateSettingsTable implements Migration
{
    /**
     * Run the migration.
     *
     * @param Pdo $connection
     */
    public function up(Pdo $connection)
    {
        $connection->createTable(
            'settings',
            null,
            [
                'columns' => [
                    new Column('id', [
                        'type'          => Column::TYPE_INTEGER,
                        'size'          => 10,
                        'unsigned'      => true,
                        'notNull'       => true,
                        'autoIncrement' => true
                    ]),
                    new Column('name', [
                        'type'    => Column::TYPE_VARCHAR,
                        'size'    => 30,
                        'notNull' => true
                    ]),
                    new Column('value', [
                        'type'    => Column::TYPE_TEXT,
                        'notNull' => true
                    ]),
                    new Column('created_date', [
                        'type'    => Column::TYPE_TIMESTAMP,
                        'notNull' => true,
                        'default' => 'CURRENT_TIMESTAMP'
                    ]),
                    new Column('updated_date', [
                        'type'    => Column::TYPE_DATETIME,
                        'default' => null
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('name_unique', ['name'], 'UNIQUE')
                ],
            ]
        );
    }

    /**
     * Reverse the migration.
     *
     * @param Pdo $connection
     */
    public function down(Pdo $connection)
    {
        $connection->dropTable('settings');
    }
}
