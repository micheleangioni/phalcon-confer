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
     * Return the User id.
     *
     * @return int
     */
    public function getUsersId()
    {
        return (int)$this->users_id;
    }

    /**
     * Return the Role id.
     *
     * @return int
     */
    public function getRolesId()
    {
        return (int)$this->roles_id;
    }

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
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersRoles[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersRoles
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
