<?php

namespace MicheleAngioni\PhalconConfer\Models;

class TeamsRoles extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->belongsTo(
            'roles_id',
            Roles::class,
            'id',
            ['alias' => 'role']
        );
    }

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $teams_id;

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
        return 'teams_roles';
    }

    /**
     * Return the Team id.
     *
     * @return int
     */
    public function getTeamId(): int
    {
        return (int)$this->teams_id;
    }

    /**
     * Set the Team id.
     *
     * @param  int $teamsId
     * @return int
     */
    public function setTeamId($teamsId)
    {
        $this->teams_id = $teamsId;
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
     * Set the Users id.
     *
     * @param  int $usersId
     * @return int
     */
    public function setUsersId($usersId)
    {
        $this->users_id = $usersId;
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

    /**
     * Set the Roles id.
     *
     * @param  int $rolesId
     * @return int
     */
    public function setRolesId($rolesId)
    {
        $this->roles_id = $rolesId;
    }

    /**
     * Change the Role id.
     * Return true on success, false otherwise.
     *
     * @param  int $rolesId
     * @return bool
     */
    public function changeRolesId($rolesId): bool
    {
        $this->roles_id = $rolesId;
        return $this->save();
    }
}
