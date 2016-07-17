<?php

namespace MicheleAngioni\PhalconConfer\Models;

abstract class AbstractConferModel extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\UsersRoles',
            'users_id', 'roles_id',
            'MicheleAngioni\PhalconConfer\Models\Roles',
            'id',
            ['alias' => 'roles']
        );

        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\UsersRoles',
            'users_id',
            ['alias' => 'rolesPivot']
        );

        $this->hasManyToMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\TeamsRoles',
            'users_id', 'roles_id',
            'MicheleAngioni\PhalconConfer\Models\Roles',
            'id',
            ['alias' => 'rolesTeam']
        );

        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\TeamsRoles',
            'users_id',
            ['alias' => 'rolesTeamPivot']
        );
    }

    /**
     * @return int
     */
    abstract public function getId();

}
