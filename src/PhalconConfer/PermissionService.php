<?php

namespace MicheleAngioni\PhalconConfer;

use MicheleAngioni\PhalconConfer\Models\Permissions;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\User\Component;

class PermissionService extends Component
{
    /**
     * @var Permissions
     */
    protected $model;

    public function __construct(Permissions $model)
    {
        $this->model = $model;
    }

    /**
     * Return all Permissions.
     *
     * @return ResultsetInterface
     */
    public function all(): ResultsetInterface
    {
        return $this->model->find();
    }

    /**
     * Retrieve and return the input Permission by name.
     *
     * @param string $name
     * @return Permissions|false
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
     * Create a new Permission.
     * $input fields: 'name'
     *
     * @param  array  $input
     * @throws \UnexpectedValueException
     *
     * @return Permissions
     */
    public function createNew(array $input): Permissions
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
