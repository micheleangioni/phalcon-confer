# Phalcon Confer

## Introduction

Phalcon Confer, or simpler Confer, empowers your app of Roles and Permissions management.   

## Installation
 
Confer can be installed through Composer, just include `"michele-angioni/phalcon-confer": "dev-master"` to your composer.json and run `composer update` or `composer install`.

## Usage

First of all you must run the Confer migrations. 
For this, you need to have installed the [Phalcon Dev Tools](https://github.com/phalcon/phalcon-devtools).

From your document root, just run `phalcon migration run --migrations=vendor/michele-angioni/phalcon-confer/migrations` .

Now let's say you have a `MyApp\Users` model you want to add roles to. 
It just need to extend the `MicheleAngioni\PhalconConfer\Models\AbstractConferModel` and use the `MicheleAngioni\PhalconConfer\ConferTrait` like so:

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

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return true;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return true;
    }
}
```

### Roles and Permission Management    
    
#### Creating a new Role

Creating a new Role is simple and can be done as a standard Phalcon model

    $role = new MicheleAngioni\PhalconConfer\Models\Roles();
    $role->save([
        'name' => 'Admin'
    ]);
    
#### Creating a new Permission

Creating a new Permission is done in the same way of a Role

    $permission = new MicheleAngioni\PhalconConfer\Models\Permissions();
    $permission->save([
        'name' => 'manage_roles'
    ]);
    
#### Assigning a Permission to a Role

Actually, assigning a Permission to a Role requires to use the Phalcon ORM

    $newPermission = [$permission];
    $role->permissions = $newPermission;
    $role->save();

#### Retrieving all Permission Roles

Thanks to the Phalcon ORM, we can immediatly retrieve all Permission from a Role

    $permissions = $role->getPermissions();

#### Assigning a Role to a User

Thanks to the ConferTrait, managing roles is extremely simple with Confer

    $user->attachRole($role);

#### Removing a Role from a User

In a way similar to the assignment, we can remove it

    $user->detachRole($role);

#### Retrieving all User Roles

Again, thanks to the Phalcon ORM, we can immediatly retrieve all Roles from a User

    $roles = $user->getRoles();

#### Checking for a Role

Checking is a User has a specific Role is straightforward

    $user->hasRole($roleName);

#### Checking for a Permission

Even checking for a specific Permission is super easy

    $user->can($permissionName);

## Contribution guidelines

Confer follows PSR-1, PSR-2 and PSR-4 PHP coding standards, and semantic versioning.

Pull requests are welcome.

## License

Confer is free software distributed under the terms of the MIT license.
    