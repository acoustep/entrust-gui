<?php namespace Acoustep\EntrustGui\Gateways;

use Hash;
use Illuminate\Config\Repository as Config;

class UserGateway {

  protected $user;
  protected $role;
  protected $config;

  public function __construct(Config $config)
  {
    $this->config = $config;
    $this->user = $this->newUserInstance();
    $this->role = $this->newRoleInstance();
  }

  public function create($request)
  {
    $data = $request->except('password');
    $data['password'] = Hash::make($request->get('password', ''));
    $user = $this->user->create($data);
    $user->roles()->sync($request->get('roles', []));
    return $user;
  }

  public function find($id)
  {
    return $this->user->with('roles')->findOrFail($id);
  }

  public function update($request, $id)
  {
    $user = $this->user->find($id);
    $data = $request->except('password');
    if($request->get('password', false)) {
      $data['password'] = Hash::make($request->get('password'));
    }
    $user->update($data);
    $user->roles()->sync($request->get('roles', []));
    return $user;
  }

  public function paginate($take = 5)
  {
    return $this->user->paginate($take);
  }

  public function newUserInstance()
  {
    $user_class = $this->config->get('auth.model');

    return new $user_class;
  }

  public function newRoleInstance()
  {
    $role_class = $this->config->get('entrust.role');

    return new $role_class;
  }

}
