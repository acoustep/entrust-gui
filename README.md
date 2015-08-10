# Entrust GUI

[![Code Climate](https://codeclimate.com/github/acoustep/entrust-gui/badges/gpa.svg)](https://codeclimate.com/github/acoustep/entrust-gui)

*This package is in very early development - breaking changes are imminent*

Entrust GUI is a Admin Interface for the Laravel Package Entrust.

Entrust GUI's goal is to make administration of users, roles and permissions easier for the [Entrust](https://github.com/Zizaco/entrust) package.

This package is currently not for handling authentication, authorisation or registration of users. 

![User Panel Preview](http://i.imgur.com/9RJ3qOi.png)

## Installation

Add the package to your ```composer.json``` file

```
"acoustep/entrust-gui": "dev-master"
```

Add the service provider to your ```config/app.php``` file

```
Acoustep\EntrustGui\EntrustGuiServiceProvider::class,
```

Add the Entrust Alias to your ```config/app.php``` file as well.

```
'Entrust'   => Zizaco\Entrust\EntrustFacade::class,
```

Publish the configuration file(s)

```
php artisan vendor:publish --tag="config"
```

If you haven't already set up Entrust then make the migration file and run the migration.

```
php artisan entrust:migration
php artisan migrate
```

Entrust GUI uses [dwight/validating](https://github.com/dwightwatson/validating) which means you can set your validation rules in your models.

Here are ```User```, ```Role``` and ```Permission``` models to get your started.

### app/User.php

```
<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Watson\Validating\ValidatingTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, ValidatingTrait, EntrustUserTrait;

    protected $throwValidationExceptions = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $rules = [
      'email'      => 'required|email|unique:users',
      'password'   => 'required',
    ];

}
```

### app/Role.php

```
<?php namespace App;

use Zizaco\Entrust\EntrustRole;
use Watson\Validating\ValidatingTrait;

class Role extends EntrustRole
{
  use ValidatingTrait;

  protected $throwValidationExceptions = true;

  protected $fillable = [
    'name',
    'display_name',
    'description',
  ];

  protected $rules = [
    'name'      => 'required|unique:roles',
  ];
}
```

### app/Permission.php

```
<?php namespace App;

use Zizaco\Entrust\EntrustPermission;
use Watson\Validating\ValidatingTrait;

class Permission extends EntrustPermission
{
  use ValidatingTrait;

  protected $fillable = [
    'name',
    'display_name',
    'description',
  ];

  protected $rules = [
    'name'      => 'required|unique:permissions',
  ];
}
```

Add the Entrust GUI middleware to ```app\Http\Kernal.php```. This middleware will allow users with the ```admin``` role to access Entrust GUI and deny other users.

```
protected $routeMiddleware = [
  'entrust-gui.admin' => \Acoustep\EntrustGui\Http\Middleware\AdminAuth::class,
];

```

At this point you're all good to go. See Getting Started for how to use the package.

## Getting Started

### Accessing Entrust GUI

By default all routes are prefixed with ```/entrust-gui```.

* Users: ```/entrust-gui/users```
* Roles: ```/entrust-gui/roles```
* Permissions: ```/entrust-gui/permissions```

You can change this prefix by editing ```route-prefix``` in ```config/entrust-gui.php```.

```
'route-prefix' => 'admin'
```

Entrust GUI uses the ```auth``` middleware. Pointing your app to ```/entrust-gui/users``` will redirect you to ```/auth/login``` if you are not logged in and using the default ```auth``` middleware.

If you have not set up Laravel authentication you will see a ```NotFoundHttpException``` exception. See the Laravel [Authentication](http://laravel.com/docs/5.1/authentication) documentation for setting up the Login system in Laravel 5.1.

### Middleware

By default Entrust GUI uses ```entrust-gui.admin``` for middleware. This allows logged in users with the ```admin``` role to access it.

You can change the middleware in ```config/entrust-gui.php``` in the middleware setting.

If you wish to test out the system without middleware then go to ```config/entrust-gui.php``` and set middleware to ```null```.

```
'middleware' => null,
```

If you want to change the name of the role that has access to Entrust GUI, update ```middleware-role``` in the configuration file.

```
"middleware-role" => 'sudo-admin',
```

### Layout

If you wish to use your own layout then override the ```layout``` key in ```config/entrust-gui.php``` with the template you wish to use.

```
"layout" => "app", // located in views/app.blade.php
```

Each template yields to ```heading``` and ```content``` so make sure your new layout has those sections.

```
<html>
<head>
  <meta charset="UTF-8">
  <title>title</title>
</head>
<body>
  <h1>@yield('heading')</h1>
  @include('entrust-gui::partials.notifications')
  @yield('content')
</body>
</html>
```

### Editing Templates

To edit the template files you first need to publish them

```
php artisan vendor:publish --tag="views"
```

All files are then stored in the ```resources/views/vendor/entrust-gui``` directory.

## To do

* Testing
* Configuration
* Advanced middleware configuration
* Translations
* Validation
* Events
* Permissions GUI
