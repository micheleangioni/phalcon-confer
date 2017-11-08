<?php

namespace MicheleAngioni\PhalconConfer\Models;

use Phalcon\Mvc\Model\Resultset;

abstract class AbstractConferTeamModel extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            TeamsRoles::class,
            'teams_id',
            'roles_id',
            Roles::class,
            'id',
            ['alias' => 'roles']
        );

        $this->hasMany(
            'id',
            TeamsRoles::class,
            'teams_id',
            ['alias' => 'rolesPivot']
        );
    }

    /**
     * @return string|int
     */
    abstract public function getId();

}
