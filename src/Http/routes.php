<?php

Route::group(['prefix' => Config::get("entrust-gui.route-prefix")], function() {
  Route::resource('users', 'UsersController');
  Route::resource('roles', 'RolesController');
  Route::resource('permissions', 'PermissionsController');
});
