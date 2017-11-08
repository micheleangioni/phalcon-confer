<?php

namespace MicheleAngioni\PhalconConfer\Models;

abstract class AbstractConferModel extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            UsersRoles::class,
            'users_id',
            'roles_id',
            Roles::class,
            'id',
            ['alias' => 'roles']
        );

        $this->hasMany(
            'id',
            UsersRoles::class,
            'users_id',
            ['alias' => 'rolesPivot']
        );

        $this->hasManyToMany(
            'id',
            TeamsRoles::class,
            'users_id',
            'roles_id',
            Roles::class,
            'id',
            ['alias' => 'rolesTeam']
        );

        $this->hasMany(
            'id',
            TeamsRoles::class,
            'users_id',
            ['alias' => 'rolesTeamPivot']
        );
    }

    /**
     * @return int
     */
    abstract public function getId(): int;

}
