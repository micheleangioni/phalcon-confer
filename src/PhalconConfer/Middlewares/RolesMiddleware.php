<?php

namespace MicheleAngioni\PhalconConfer\Middlewares;

use Phalcon\Mvc\User\Plugin;

/**
 * This middleware aims to protect private routes from unauthorized access.
 *
 * @package MicheleAngioni\PhalconConfer
 */
class RolesMiddleware extends Plugin
{
    /**
     * The Role name.
     *
     * @var string
     */
    protected $roleName;

    /**
     * @param string $roleName
     */
    function __construct(string $roleName)
    {
        $this->roleName = $roleName;
    }

    /**
     * Check if there is an Authenticated User and if he/she has the required Role.
     *
     * @return mixed
     */
    public function check()
    {
        // Check if the User is session authenticated

        $auth = $this->getDI()->get('auth');

        try {
            $user = $auth->getAuth();
        } catch (\Exception $e) {
            // Session saved but User not found! Destroy session and redirect
            $auth->logout();

            return false;
        }

        // Check if an authenticated User has been found

        if (!$user) {
            // The User is not authenticated, return false
            return false;
        }

        // Check if the User has the required role

        if(!$user->hasRole($this->roleName)) {
            // The User has not the required role, return false
            return false;
        }

        return true;
    }
}
