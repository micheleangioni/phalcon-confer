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
     * Model initialization.
     */
    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            RolesPermissions::class,
            'roles_id',
            'permissions_id',
            Permissions::class,
            'id',
            ['alias' => 'permissions']
        );

        // The permissionsPivot relationship is used only when deleting a Role
        $this->hasMany(
            'id',
            RolesPermissions::class,
            'roles_id',
            ['alias' => 'permissionsPivot']
        );

        // The usersPivot relationship is used only when deleting a Role
        $this->hasMany(
            'id',
            UsersRoles::class,
            'roles_id',
            ['alias' => 'usersPivot']
        );
    }

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
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * Return the Role name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the Role name.
     *
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return the created at date.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Return the updated at date.
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Delete relationships on cascade before deleting the Role.
     *
     * @return bool
     */
    public function delete(): bool
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
    public function attachPermission(Permissions $permission): bool
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
    public function detachPermission(Permissions $permission): bool
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
}
