<?php

namespace MicheleAngioni\PhalconConfer\Models;

class Roles extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $created_at;

    /**
     *
     * @var string
     */
    protected $updated_at;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'roles';
    }

    /**
     * Return the Role id.
     *
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * Return the Role name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return the created at date.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Return the updated at date.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Model initialization.
     */
    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\RolesPermissions',
            'roles_id', 'permissions_id',
            'MicheleAngioni\PhalconConfer\Models\Permissions',
            'id',
            ['alias' => 'permissions']
        );

        // The permissionsPivot relationship is used only when deleting a Role
        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\RolesPermissions',
            'roles_id',
            ['alias' => 'permissionsPivot']
        );

        // The usersPivot relationship is used only when deleting a Role
        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\UsersRoles',
            'roles_id',
            ['alias' => 'usersPivot']
        );
    }

    /**
     * Delete relationships on cascade before deleting the Role.
     */
    public function delete()
    {
        $this->getPermissionsPivot()->delete();
        $this->getUsersPivot()->delete();

        return parent::delete();
    }


    /**
     * Attach input Permission to the Role.
     * Return true on success.
     *
     * @param  Permissions $permission
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     *
     * @return bool
     */
    public function attachPermission(Permissions $permission)
    {
        // Check if input Permission is already attached to the Role

        foreach ($this->getPermissions() as $rolePermission) {
            if ($rolePermission->id == $permission->id) {
                return true;
            }
        }

        $newPermission = [$permission];
        $this->permissions = $newPermission;

        try {
            $result = $this->save();
        } catch (\Exception $e) {
            throw new \RuntimeException("Caught RuntimeException in " . __METHOD__ . ' at line ' . __LINE__ . ': ' . $e->getMessage());
        }

        if (!$result) {
            $errorMessages = implode('. ', $this->getMessages());
            throw new \UnexpectedValueException("Caught UnexpectedValueException in " . __METHOD__ . ' at line ' . __LINE__ . ': $role ' . $permission->id . ' cannot be attached to user ' . $this->id . '. Error messages: ' . $errorMessages);
        }

        return true;
    }

    /**
     * Detach input input Permission to the Role.
     * Return true on success.
     *
     * @param  Permissions $permission
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     *
     * @return bool
     */
    public function detachPermission(Permissions $permission)
    {
        // Check if input Permission is attached to the Role

        $permissions = $this->getPermissionsPivot();

        foreach ($permissions as $permissionPivot) {
            if ($permissionPivot->getPermissionsId() == $permission->id) {
                try {
                    $result = $permissionPivot->delete();
                } catch (\Exception $e) {
                    throw new \RuntimeException("Caught RuntimeException in " . __METHOD__ . ' at line ' . __LINE__ . ': ' . $e->getMessage());
                }

                if (!$result) {
                    $errorMessages = implode('. ', $this->getMessages());
                    throw new \UnexpectedValueException("Caught UnexpectedValueException in " . __METHOD__ . ' at line ' . __LINE__ . ': $role ' . $permission->id . ' cannot be detached from user ' . $this->id . '. Error messages: ' . $errorMessages);
                }
            }
        }

        return true;
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return Roles[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return Roles
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
