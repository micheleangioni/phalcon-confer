<?php

namespace MicheleAngioni\PhalconConfer;

use MicheleAngioni\PhalconConfer\Models\Roles;

trait ConferTrait
{
    /**
     * Check if the User has input Role.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->getRoles() as $role) {
            if ($role->getName() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the User has input Role in input Team.
     *
     * @param  int $idTeam
     * @param  string $roleName
     *
     * @return bool
     */
    public function hasRoleInTeam($idTeam, $roleName)
    {
        foreach ($this->getRolesTeamPivot([
            "teams_id = :teams_id:",
            "bind" => [
                "teams_id" => $idTeam
            ]
        ]) as $teamRole) {
            $role = $teamRole->getRole();

            if ($role->getName() == $roleName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the has input Permission.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function can($name)
    {
        foreach ($this->getRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                if ($permission->getName() == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the has input Permission.
     *
     * @param  int $idTeam
     * @param  string $permissionName
     *
     * @return bool
     */
    public function canInTeam($idTeam, $permissionName)
    {
        foreach ($this->getRolesTeamPivot([
            "users_id = :teams_id:",
            "bind" => [
                "teams_id" => $idTeam
            ]
        ]) as $teamRole) {
            $role = $teamRole->getRole();

            foreach ($role->getPermissions() as $permission) {
                if ($permission->getName() == $permissionName) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Attach input Role to the User.
     * Return true on success.
     *
     * @param  Roles $role
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     *
     * @return bool
     */
    public function attachRole(Roles $role)
    {
        // Check if input Role is already attached to the User

        foreach ($this->getRoles() as $userRole) {
            if ($userRole->id == $role->id) {
                return true;
            }
        }

        $newRole = [$role];
        $this->roles = $newRole;

        try {
            $result = $this->save();
        } catch (\Exception $e) {
            throw new \RuntimeException("Caught RuntimeException in " . __METHOD__ . ' at line ' . __LINE__ . ': ' . $e->getMessage());
        }

        if (!$result) {
            $errorMessages = implode('. ', $this->getMessages());
            throw new \UnexpectedValueException("Caught UnexpectedValueException in " . __METHOD__ . ' at line ' . __LINE__ . ': $role ' . $role->id . ' cannot be attached to user ' . $this->id . '. Error messages: ' . $errorMessages);
        }

        return true;
    }

    /**
     * Detach input Role from the User.
     * Return true on success.
     *
     * @param  Roles $role
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     *
     * @return bool
     */
    public function detachRole(Roles $role)
    {
        // Check if input Role is attached to the User

        $rolesPivot = $this->getRolesPivot();

        foreach ($rolesPivot as $rolePivot) {
            if ($rolePivot->getRolesId() == $role->id) {
                try {
                    $result = $rolePivot->delete();
                } catch (\Exception $e) {
                    throw new \RuntimeException("Caught RuntimeException in " . __METHOD__ . ' at line ' . __LINE__ . ': ' . $e->getMessage());
                }

                if (!$result) {
                    $errorMessages = implode('. ', $this->getMessages());
                    throw new \UnexpectedValueException("Caught UnexpectedValueException in " . __METHOD__ . ' at line ' . __LINE__ . ': $role ' . $role->id . ' cannot be detached from user ' . $this->id . '. Error messages: ' . $errorMessages);
                }
            }
        }

        return true;
    }
}
