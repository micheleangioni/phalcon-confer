<?php

namespace MicheleAngioni\PhalconConfer;

use MicheleAngioni\PhalconConfer\Models\Permissions;
use MicheleAngioni\PhalconConfer\Models\Roles;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\User\Component;

class Confer extends Component
{
    /**
     * @var RoleService
     */
    private $roleService;

    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;

        $this->permissionService = $permissionService;
    }

    /**
     * Return all Roles.
     *
     * @return ResultsetInterface
     */
    public function getRoles(): ResultsetInterface
    {
        return $this->roleService->all();
    }

    /**
     * Retrieve and return the input Role by name.
     *
     * @param string $name
     * @return Roles|false
     */
    public function getRole(string $name)
    {
        return $this->roleService->findByName($name);
    }

    /**
     * Create a new Role.
     *
     * @param string $name
     * @throws \UnexpectedValueException
     *
     * @return Roles
     */
    public function createRole(string $name): Roles
    {
        return $this->roleService->createNew([
            'name' => $name
        ]);
    }

    /**
     * Return all Permissions.
     *
     * @return ResultsetInterface
     */
    public function getPermissions(): ResultsetInterface
    {
        return $this->permissionService->all();
    }

    /**
     * Retrieve and return the input Permission by name.
     *
     * @param string $name
     * @return Permissions|false
     */
    public function getPermission(string $name)
    {
        return $this->permissionService->findByName($name);
    }

    /**
     * Create a new Permission.
     *
     * @param  string $name
     * @throws \UnexpectedValueException
     *
     * @return Permissions
     */
    public function createPermission(string $name): Permissions
    {
        return $this->permissionService->createNew([
            'name' => $name
        ]);
    }
}
