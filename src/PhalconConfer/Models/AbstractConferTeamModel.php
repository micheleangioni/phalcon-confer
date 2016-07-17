<?php

namespace MicheleAngioni\PhalconConfer\Models;

use Phalcon\Mvc\Model\Resultset;

abstract class AbstractConferTeamModel extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\TeamsRoles',
            'teams_id', 'roles_id',
            'MicheleAngioni\PhalconConfer\Models\Roles',
            'id',
            ['alias' => 'roles']
        );

        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\TeamsRoles',
            'teams_id',
            ['alias' => 'rolesPivot']
        );
    }

    /**
     * @return int
     */
    abstract public function getId();

}
