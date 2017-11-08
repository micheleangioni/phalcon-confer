# Confer

[![License](https://poser.pugx.org/michele-angioni/phalcon-confer/license)](https://packagist.org/packages/michele-angioni/phalcon-confer)
[![Latest Stable Version](https://poser.pugx.org/michele-angioni/phalcon-confer/v/stable)](https://packagist.org/packages/michele-angioni/phalcon-confer)
[![Latest Unstable Version](https://poser.pugx.org/michele-angioni/phalcon-confer/v/unstable)](https://packagist.org/packages/michele-angioni/phalcon-confer)
[![Build Status](https://travis-ci.org/micheleangioni/phalcon-confer.svg)](https://travis-ci.org/micheleangioni/phalcon-confer)

## Introduction

Phalcon Confer, or simply Confer, empowers your appplication of a powerful yet flexible Roles and Permissions management system.   

Either Users or User Teams can receive new Roles.

Confer has been highly inspired by the Laravel package [Entrust](https://github.com/Zizaco/entrust). 

## Installation
 
Confer can be installed through Composer, just include `"michele-angioni/phalcon-confer": "^1.0"` to your composer.json and run `composer update` or `composer install`.

Then Confer migrations must run to create the needed tables. 
For this, you need to have installed the [Phalcon Dev Tools](https://github.com/phalcon/phalcon-devtools).

From your Phalcon document root, just run `phalcon migration run --migrations=vendor/michele-angioni/phalcon-confer/migrations` .

## Usage

### Empowering Users

Let's say you have a `MyApp\Users` model you want to add roles to. 
It just needs to extend `MicheleAngioni\PhalconConfer\Models\AbstractConferModel` and use `MicheleAngioni\PhalconConfer\ConferTrait` like so:

```php
<?php

namespace MyApp;

use MicheleAngioni\PhalconConfer\ConferTrait;
use MicheleAngioni\PhalconConfer\Models\AbstractConferModel;

class Users extends AbstractConferModel
{
    use ConferTrait;

    protected $id;

    protected $email;

    protected $password;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}
```

### Empowering Teams

Alternatively, if your application has User Teams and you need to empower Teams of a Roles and Permission system, Confer can do it.
 
Let's say you also have a `MyApp\Teams` model and you want to add roles to Users separately for each Team they belong. 
You just need to also extend `MicheleAngioni\PhalconConfer\Models\AbstractConferTeamModel` and the `MicheleAngioni\PhalconConfer\ConferTeamTrait` to your Team model like so:

```php
<?php

namespace MyApp;

use MicheleAngioni\PhalconConfer\ConferTeamTrait;
use MicheleAngioni\PhalconConfer\Models\AbstractConferTeamModel;

class Teams extends AbstractConferTeamModel
{
    use ConferTeamTrait;

    protected $id;

    protected $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
```

### Roles and Permission Management    
    
#### Creating a new Role

Creating a new Role is simple and can be done as a standard Phalcon model

```php
$role = new MicheleAngioni\PhalconConfer\Models\Roles();
$role->save([
    'name' => 'Admin'
]);
```
    
#### Creating a new Permission

Creating a new Permission is done in the same way of a Role

```php
$permission = new MicheleAngioni\PhalconConfer\Models\Permissions();
$permission->save([
    'name' => 'manage_roles'
]);
```
    
#### Assigning a Permission to a Role

Assigning a Permission to a Role is straightforward

```php
$role->attachPermission($permission);
```

#### Removing a Permission from a Role

Same as before, removing a Permission from a Role is achieved with a single command

```php
$role->detachPermission($permission);
```

#### Retrieving all Permission Roles

Thanks to the Phalcon ORM, we can immediatly retrieve all Permission from a Role

```php
$permissions = $role->getPermissions();
```

### User Roles Management  

#### Assigning a Role to a User

Thanks to the ConferTrait, managing roles is extremely simple with Confer

```php
$user->attachRole($role);
```

#### Removing a Role from a User

In a way similar to the assignment, we can remove it

```php
$user->detachRole($role);
```

#### Retrieving all User Roles

Again, thanks to the Phalcon ORM, we can immediatly retrieve all Roles from a User

```php
$roles = $user->getRoles();
```

#### Checking for a Role

Checking if a User has a specific Role is straightforward

```php
$user->hasRole($roleName);
```

#### Checking for a Permission

Even checking for a specific Permission is super easy

```php
$user->can($permissionName);
```

### Team User Roles Management

#### Assigning a Role to a Team

Thanks to the ConferTeamTrait, even Team Roles can be handled without efforts 

```php
$team->attachRole($idUser, $role);
```

#### Removing a Role from a Team

In a way similar to the assignment, we can remove it

```php
$team->detachRole($idUser);
```

#### Checking for a Role

Checking if a Team User has a specific Role can be done both from the User and Team models

```php
$user->hasRoleInTeam($idTeam, $roleName);
$team->userHasRole($idUser, $roleName);
```

#### Checking for a Permission

Also checking for a specific Permission can be performed from both models

```php
$team->userCan($idUser, $permissionName);
$team->canInTeam($idTeam, $permissionName);
```

### Middlewares

Once you have set your own Roles and Permissions, it is likely you want to protect some of your routes. 
The simplest way to achieve that is to use the Match Callback feature of the Phalcon Router. 
You can easily write your custom RolesMiddleware or use the one included in Confer.

#### Custom Match Callback

Let's build a custom RolesMiddleware skeleton so you can easily add it to your application

```php
<?php

namespace MyApp;

use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

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

    function __construct(string $roleName, $callbackUri = null)
    {
        $this->roleName = $roleName;

        $this->callbackUri = $callbackUri;
    }

    /**
     * Check if there is an Authenticated User and if he/she has the required Role.
     *
     * @return mixed
     */
    public function check()
    {
        // 1) Check if a User if currently authenticated. You can do this with your own auth service or however you prefer
           
        [...]  // Let's say we have a $user object which can be null, false or an instance of MyApp\Users
        
        // 2) Check if an authenticated User has been found

        if (!$user) {
            // The User is not authenticated, return false or redirect to callbackUri
            if ($this->callbackUri) {
                $response = new Response();
                return $response->redirect($this->callbackUri);
            } else {
                return false;
            }
        }

        // 3) We have the authenticated User. Check if he/she has the required role

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
```

Now that we have our RolesMiddleware, just add it to the router and check if the user has the `'Admin'` Role

```php
$router->addGet('/super-private-route', [
    'module' => 'web',
    'controller' => 'secret',
    'action' => 'index'
])->beforeMatch([new \MyApp\RolesMiddleware('DEV', '/homepage'), 'check']);
```

That's it. If the User has not the required Role, he/she will get a 404 error.

#### Confer RolesMiddleware

Confer comes with an own RolesMiddleware out of the box.
However, in order to use it, the User authentication must be handled by [Phalcon Auth](https://github.com/micheleangioni/phalcon-auth).

Phalcon Auth allow you to easily retrieve the authenticated user by just calling `$auth->getAuth()`.

If you are using Phalcon Auth to handle your authentication, adding the Confer RolesMiddleware is straightforward

```php
$router->addGet('/super-private-route', [
    'module' => 'web',
    'controller' => 'secret',
    'action' => 'index'
])->beforeMatch([new \MicheleAngioni\PhalconConfer\Middlewares('DEV', '/homepage'), 'check']);
```

## Contribution guidelines

Confer follows PSR-1, PSR-2 and PSR-4 PHP coding standards, and semantic versioning.

Pull requests are welcome.

## License

Confer is free software distributed under the terms of the MIT license.
