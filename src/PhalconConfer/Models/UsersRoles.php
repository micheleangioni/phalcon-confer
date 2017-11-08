<?php

namespace MicheleAngioni\PhalconConfer\Models;

class UsersRoles extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $users_id;

    /**
     *
     * @var integer
     */
    protected $roles_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users_roles';
    }

    /**
     * Return the User id.
     *
     * @return int
     */
    public function getUsersId(): int
    {
        return (int)$this->users_id;
    }

    /**
     * Return the Role id.
     *
     * @return int
     */
    public function getRolesId(): int
    {
        return (int)$this->roles_id;
    }
}
