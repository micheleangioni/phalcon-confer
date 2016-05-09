<?php

namespace MicheleAngioni\PhalconConfer\Models;

class RolesPermissions extends \Phalcon\Mvc\Model
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
    protected $roles_id;

    /**
     *
     * @var integer
     */
    protected $permissions_id;

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
     * Return the Permission id.
     *
     * @return int
     */
    public function getPermissionsId()
    {
        return (int)$this->permissions_id;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'roles_permissions';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolesPermissions[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolesPermissions
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
