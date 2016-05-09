<?php

namespace MicheleAngioni\PhalconConfer\Models;

class Permissions extends \Phalcon\Mvc\Model
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
        return 'permissions';
    }

    /**
     * Return the Permission id.
     *
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * Return the Permission name.
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
        $this->hasMany(
            'id',
            'MicheleAngioni\PhalconConfer\Models\RolesPermissions',
            'permissions_id',
            ['alias' => 'rolesPivot']
        );
    }

    /**
     * Delete relationships on cascade before deleting the Role.
     */
    public function delete()
    {
        $this->getRolesPivot()->delete();

        return parent::delete();
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return Permissions[]
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
     * @return Permissions
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
