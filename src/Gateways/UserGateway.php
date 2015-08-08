<?php namespace Acoustep\EntrustGui\Gateways;

use Hash;
use Illuminate\Config\Repository as Config;
use Acoustep\EntrustGui\Repositories\UserRepository;

class UserGateway {

  protected $user_repository;
  protected $role;
  protected $config;

  public function __construct(Config $config, UserRepository $user_repository)
  {
    $this->config = $config;
    $this->user_repository = $user_repository;
    $this->role = $this->newRoleInstance();
  }

  public function create($request)
  {
    $data = $request->except('password');
    $data['password'] = Hash::make($request->get('password', ''));
    $user = $this->user_repository->create($data);
    $user->roles()->sync($request->get('roles', []));
    return $user;
  }

  public function find($id)
  {
    return $this->user_repository->with('roles')->find($id);
  }

  public function update($request, $id)
  {
    $user = $this->user_repository->find($id);
    $data = $request->except('password');
    if($request->get('password', false)) {
      $data['password'] = Hash::make($request->get('password'));
    }
    $user->update($data);
    $user->roles()->sync($request->get('roles', []));
    return $user;
  }

  public function delete($id)
  {
    $this->user_repository->delete($id);
  }

  public function paginate($take = 5)
  {
    return $this->user_repository->paginate($take);
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
