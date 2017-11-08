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
    public function getRolesId(): int
    {
        return (int)$this->roles_id;
    }

    /**
     * Return the Permission id.
     *
     * @return int
     */
    public function getPermissionsId(): int
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
}
