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
     * Model initialization.
     */
    public function initialize()
    {
        $this->hasMany(
            'id',
            RolesPermissions::class,
            'permissions_id',
            ['alias' => 'rolesPivot']
        );
    }

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
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * Return the Permission name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the Permission name.
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
        $this->getRolesPivot()->delete();

        return parent::delete();
    }
}
