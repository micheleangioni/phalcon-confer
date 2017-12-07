<?php

namespace MicheleAngioni\PhalconConfer;

use MicheleAngioni\PhalconConfer\Models\Roles;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\User\Component;

class RoleService extends Component
{
    /**
     * @var Roles
     */
    protected $model;

    public function __construct(Roles $model)
    {
        $this->model = $model;
    }

    /**
     * Return all Roles.
     *
     * @return ResultsetInterface
     */
    public function all(): ResultsetInterface
    {
        return $this->model->find();
    }

    /**
     * Retrieve and return the input Role.
     *
     * @param int $id
     * @return Roles|false
     */
    public function find(int $id)
    {
        return $this->model->findFirst(["id = :value:", 'bind' => ['value' => $id]]);
    }

    /**
     * Retrieve and return the input Role by name.
     *
     * @param string $name
     * @return Roles|false
     */
    public function findByName(string $name)
    {
        $query = $this->model->query();
        $query = $query->where(
                'name = :name:',
                [
                    'name' => $name
                ]
            );

        return $query->limit(1)->execute()->getFirst();
    }

    /**
     * Create a new Role.
     * $input fields: 'name'
     *
     * @param  array  $input
     * @throws \UnexpectedValueException
     *
     * @return Roles
     */
    public function createNew(array $input): Roles
    {
        $data = [
            'name' => $input['name'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $model = clone $this->model;

        $result = $model->create($data);

        if (!$result) {
            $errorMessages = '';

            foreach ($model->getMessages() as $message) {
                $errorMessages .= $message . '. ';
            }

            throw new \UnexpectedValueException("Caught UnexpectedValueException in " . __METHOD__ . ' at line ' . __LINE__ . ': Model cannot be saved. Error messages: ' . $errorMessages);
        }

        return $model;
    }
}
