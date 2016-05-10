<?php

namespace MicheleAngioni\PhalconConfer\Middlewares;

use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

/**
 * This middleware need to protect private routes from unauthorized access.
 * Requires the package MicheleAngioni\PhalconAuth.
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
     * The uri the User will be redirected to if he/she has not the required Role.
     *
     * @var string|null
     */
    protected $callbackUri;

    function __construct($roleName, $callbackUri = null)
    {
        $this->roleName = $roleName;

        $this->callbackUri = $callbackUri;
    }

    /**
     * Check if there is an Authenticated User and if he/she has the required Role.
     *
     * @return bool
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

            if ($this->callbackUri) {
                $response = new Response();
                return $response->redirect($this->callbackUri);
            } else {
                return false;
            }
        }

        // Check if an authenticated User has been found

        if (!$user) {
            // The User is not authenticated, return false or redirect to callbackUri
            if ($this->callbackUri) {
                $response = new Response();
                return $response->redirect($this->callbackUri);
            } else {
                return false;
            }
        }

        // Check if the User has the required role

        if(!$user->hasRole($this->roleName)) {
            // The User has not the required role, return false or redirect to callbackUri
            if ($this->callbackUri) {
                $response = new Response();
                return $response->redirect($this->callbackUri);
            } else {
                return false;
            }
        }

        return true;
    }
}
