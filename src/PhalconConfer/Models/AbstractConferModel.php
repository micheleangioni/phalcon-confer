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
    }

}
