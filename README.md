# Entrust GUI

[![Code Climate](https://codeclimate.com/github/acoustep/entrust-gui/badges/gpa.svg)](https://codeclimate.com/github/acoustep/entrust-gui)
[![Build Status](https://travis-ci.org/acoustep/entrust-gui.svg?branch=testing)](https://travis-ci.org/acoustep/entrust-gui)
[![Latest Stable Version](https://poser.pugx.org/acoustep/entrust-gui/v/stable)](https://packagist.org/packages/acoustep/entrust-gui)
[![Total Downloads](https://poser.pugx.org/acoustep/entrust-gui/downloads)](https://packagist.org/packages/acoustep/entrust-gui)
[![Latest Unstable Version](https://poser.pugx.org/acoustep/entrust-gui/v/unstable)](https://packagist.org/packages/acoustep/entrust-gui)
[![License](https://poser.pugx.org/acoustep/entrust-gui/license)](https://packagist.org/packages/acoustep/entrust-gui)

Entrust GUI is a Admin Interface that makes the administration of users, roles and permissions easier for the [Entrust](https://github.com/Zizaco/entrust) package.

This package is currently not for handling authentication, authorisation or registration of users. 

![User Panel Preview](http://i.imgur.com/9RJ3qOi.png)

## Installation

Add the package to your ```composer.json``` file

```
"acoustep/entrust-gui": "5.2.x-dev",
"zizaco/entrust": "5.2.x-dev"
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

Add the ```table``` key with the value ```users``` to the ```providers.users``` array in ```config/auth.php```.

```
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
            'table' => 'users',
        ],
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
```

If you haven't already set up Entrust then make the migration file and run the migration.

```
php artisan entrust:migration
php artisan migrate
```

Entrust GUI uses [esensi/model](https://github.com/esensi/model) which means you can set your validation rules in your models.

To generate ```User```, ```Role``` and ```Permission``` models run the ```entrust-gui:models``` command.

```
php artisan entrust-gui:models
```

See the **manually creating models** section if you prefer to adjust your current model files.

By default, all three files are published into the ```app_path()``` directory. You can specify the files separately and the location 

Add the Entrust GUI middleware to ```app\Http\Kernal.php```. This middleware will allow users with the role ```admin``` (case sensitive) to access Entrust GUI and deny other users.

```
protected $routeMiddleware = [
  // ...
  'entrust-gui.admin' => \Acoustep\EntrustGui\Http\Middleware\AdminAuth::class,
];

```

Finally, there is currently an issue with Entrust which requires you to set the cache driver to array. This may change in the future. You can change this setting in your ```.env``` file.

```
CACHE_DRIVER=array
```

At this point you're all good to go. See Getting Started for how to use the package.

## Getting Started

### Using Laravel Authentication

Run the auth command if you haven't already set it up.

```
php artisan make:auth
```

### Accessing Entrust GUI

By default all routes are prefixed with ```/entrust-gui```.

* Users: ```/entrust-gui/users```
* Roles: ```/entrust-gui/roles```
* Permissions: ```/entrust-gui/permissions```

You can change this prefix by editing ```route-prefix``` in ```config/entrust-gui.php```.

```
'route-prefix' => 'admin'
```

Pointing your app to ```/entrust-gui/users``` will redirect you to ```/auth/login``` if you are not logged in as admin using the default ```entrust-gui.admin``` middleware.

If you have not set up Laravel authentication you will see a ```NotFoundHttpException``` exception. See the Laravel [Authentication](http://laravel.com/docs/5.1/authentication) documentation for setting up the Login system in Laravel 5.1.

### Middleware

By default Entrust GUI uses ```entrust-gui.admin``` for middleware. This allows logged in users with the ```admin``` role to access it.

You can change the middleware in ```config/entrust-gui.php``` in the ```middleware``` setting.

If you wish to test out the system without middleware then go to ```config/entrust-gui.php```, remove ```entrust-gui.admin``` middleware and keep ```web``` which is required for flash messages to work correctly in Laravel 5.2.


```
'middleware' => 'web',
```

If you want to change the name of the role that has access to the ```admin``` middleware, update ```middleware-role``` in the configuration file.

```
"middleware-role" => 'sudo-admin',
```

### Layout

To use your own layout override the ```layout``` key in ```config/entrust-gui.php``` with the template you wish to use.

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

### Routes

You may wish to link to pages in your own templates. EntrustGUI follows Laravel's ```Route::resource``` method with the exception of ```show```.

```
route('entrust-gui::users.index')
route('entrust-gui::users.create')
route('entrust-gui::users.destroy', $id)
route('entrust-gui::users.update', $id)
route('entrust-gui::users.edit', $id)
```

### Events

The following event classes are available:

* ```UserCreatedEvent```, ```UserDeletedEvent```, ```UserUpdatedEvent```.
* ```RoleCreatedEvent```, ```RoleDeletedEvent```, ```RoleUpdatedEvent```.
* ```PermissionCreatedEvent```, ```PermissionDeletedEvent```, ```PermissionUpdatedEvent```.

#### Example Event Listener

```
<?php

namespace App\Listeners;

use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class UserCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
      Log::info('created: '.$event->user->email);
    }
}
```

Add the listeners you need to use to ```app/Providers/EventServiceProvider.php```.

```
protected $listen = [
  'Acoustep\EntrustGui\Events\UserCreatedEvent' => [
    'App\Listeners\UserCreatedListener',
  ],
  'Acoustep\EntrustGui\Events\UserUpdatedEvent' => [
    'App\Listeners\UserUpdatedListener',
  ],
  'Acoustep\EntrustGui\Events\UserDeletedEvent' => [
    'App\Listeners\UserDeletedListener',
  ],
  'Acoustep\EntrustGui\Events\RoleCreatedEvent' => [
    'App\Listeners\RoleCreatedListener',
  ],
  'Acoustep\EntrustGui\Events\RoleUpdatedEvent' => [
    'App\Listeners\RoleUpdatedListener',
  ],
  'Acoustep\EntrustGui\Events\RoleDeletedEvent' => [
    'App\Listeners\RoleDeletedListener',
  ],
  'Acoustep\EntrustGui\Events\PermissionCreatedEvent' => [
    'App\Listeners\PermissionCreatedListener',
  ],
  'Acoustep\EntrustGui\Events\PermissionUpdatedEvent' => [
    'App\Listeners\PermissionUpdatedListener',
  ],
  'Acoustep\EntrustGui\Events\PermissionDeletedEvent' => [
    'App\Listeners\PermissionDeletedListener',
  ]
];
```

### Editing Translations

Run the publish translation command

```
php artisan vendor:publish --tag="translations"
```

Translations are then published to ```resources/lang/vendor/entrust-gui```.

### Adding Password Confirmation Field to Users

Update your ```User``` model to the following:

```
<?php namespace App;

use Esensi\Model\Contracts\PurgingModelInterface;
use Esensi\Model\Traits\PurgingModelTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Acoustep\EntrustGui\Contracts\HashMethodInterface;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, ValidatingModelInterface, HashMethodInterface, PurgingModelInterface
{
    use Authenticatable, CanResetPassword, ValidatingModelTrait, EntrustUserTrait, PurgingModelTrait;

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
    protected $fillable = ['name', 'email', 'password', 'password_confirmation'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $purgeable = [
        'password_confirmation',
    ];

    protected $hashable = ['password'];

    protected $rulesets = [

        'creating' => [
            'email'      => 'required|email|unique:users',
            'password'   => 'required|confirmed',
        ],

        'updating' => [
            'email'      => 'required|email|unique:users',
            'password'   => 'confirmed',
        ],
    ];

    public function entrustPasswordHash() 
    {
        $this->password = Hash::make($this->password);
        $this->forceSave();
    }
}
```

Update ```config/entrust-gui.php```

```
'confirmable' => true,
```

### Generating Models Command Options

Generating a specific model

```
php artisan entrust-gui:models User
```

Changing the model directory destination

```
php artisan entrust-gui:models --path=new/path
```

Skipping confirmation prompts for overwriting existing files

```
php artisan entrust-gui:models --force
```


### Manually Creating Models

Here are ```User```, ```Role``` and ```Permission``` models. Make sure these parameters are and traits are included for the package to work as intended. 

#### app/User.php

```
<?php namespace App;

use Esensi\Model\Contracts\ValidatingModelInterface;
use Esensi\Model\Traits\ValidatingModelTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Acoustep\EntrustGui\Contracts\HashMethodInterface;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, ValidatingModelInterface, HashMethodInterface
{
  use Authenticatable, CanResetPassword, ValidatingModelTrait, EntrustUserTrait;

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

    protected $hashable = ['password'];

    protected $rulesets = [

        'creating' => [
            'email'      => 'required|email|unique:users',
            'password'   => 'required',
        ],

        'updating' => [
            'email'      => 'required|email|unique:users',
            'password'   => '',
        ],
    ];

    public function entrustPasswordHash() 
    {
        $this->password = Hash::make($this->password);
        $this->save();
    }

}
```

#### app/Role.php

```
<?php namespace App;

use Esensi\Model\Contracts\ValidatingModelInterface;
use Esensi\Model\Traits\ValidatingModelTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole implements ValidatingModelInterface
{
  use ValidatingModelTrait;

  protected $throwValidationExceptions = true;

  protected $fillable = [
    'name',
    'display_name',
    'description',
  ];

  protected $rules = [
    'name'      => 'required|unique:roles',
    'display_name'      => 'required|unique:roles',
  ];
}
```

#### app/Permission.php

```
<?php namespace App;

use Esensi\Model\Contracts\ValidatingModelInterface;
use Esensi\Model\Traits\ValidatingModelTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission implements ValidatingModelInterface
{
  use ValidatingModelTrait;

  protected $throwValidationExceptions = true;

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

### Custom queries, sorting and filters for users

If you would like to search, sort and filter the users you can use the l5-repository searchable fields feature.

In your entrust-gui.php configuration file, add the fields that you wish to be searchable 

```
    "users" => [
       'fieldSearchable' => [
           'email'
       ],
     ],
```
You can now create links such as the following:

* http://localhost.dev/entrust-gui/users?search=John%20Doe
* http://localhost.dev/entrust-gui/users?search=email&searchFields=email:like
* http://localhost.dev/entrust-gui/users?search=email&searchFields=email:like&orderBy=email&sortedBy=asc
* http://tcascompressors.dev/admin/users?filter=email

You can read more about these options [here](https://github.com/andersao/l5-repository#using-the-requestcriteria)

## Upgrade Guide / Breaking Changes

### 5.2 Branch

Add the following to the ```config/entrust-gui.php``` configuration file to enable custom sorting and filters

```
    "users" => [
       'fieldSearchable' => [],
     ],
```

Optional removal of ```Esensi\Model\Traits\HashingModelTrait```. Replaced with ```Acoustep\EntrustGui\Contracts\HashMethodInterface``` and allows users to set their own password hashing method for within the Entrust GUI. For the default Laravel 5.2 Auth it's recommended to use the following snippet in your User model.

```
use Acoustep\EntrustGui\Contracts\HashMethodInterface;
use Hash;
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, ValidatingModelInterface, HashMethodInterface
    // ...
    public function entrustPasswordHash() 
    {
        $this->password = hash::make($this->password);
        $this->save();
    }
}
```

### 0.6

Includes a new config key, ```"unauthorized-url"``` which lets you set the redirection if a user is not authorized. If this key is not found, it will use the old url, ```/auth/login```. The default is set to ```/login``` to match Laravel 5.2's built in authentication route.

### 0.3.* to 0.4.0

Starting from 0.4.0 Entrust GUI switches from ```dwightwatson/validating``` to ```esensi/model```. 

Hashing passwords has moved from the package to the ```User``` model.

Update your ```User``` model to the one in the latest documentation.

Add ```'confirmable' => false,``` to your configuration file.

If you intend to use the confirmable option and have already published the views add the following to your ```resources/views/vendor/entrust-gui/users/partials/form.blade.php``` template

```
@if(Config::get('entrust-gui.confirmable') === true)
<div class="form-group">
    <label for="password">Confirm Password</label>
    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation">
</div>
@endif
```

## To do

* Advanced middleware configuration
* More testing
* More documentation
* Animated video preview
