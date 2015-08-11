<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\UserRepository;
use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Acoustep\EntrustGui\Events\UserUpdatedEvent;
use Acoustep\EntrustGui\Events\UserDeletedEvent;
use Hash;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

class UserGateway {

  protected $user_repository;
  protected $role;
  protected $config;
  protected $dispatcher;

  public function __construct(Config $config, UserRepository $user_repository, Dispatcher $dispatcher)
  {
    $this->config = $config;
    $this->user_repository = $user_repository;
    $role_class = $this->config->get('entrust.role');
    $this->role = new $role_class;
    $this->dispatcher = $dispatcher;
  }

  public function create($request)
  {
    $data = $request->except('password');
    $data['password'] = ($request->get('password', false)) ? Hash::make($request->get('password', '')) : '';
    $user = $this->user_repository->create($data);
    $user->roles()->sync($request->get('roles', []));

    $this->dispatcher->fire(new UserCreatedEvent($user));
    return $user;
  }

  public function find($id)
  {
    return $this->user_repository->with('roles')->find($id);
  }

  public function update($request, $id)
  {
    $data = $request->except('password');
    if($request->get('password', false)) {
      $data['password'] = Hash::make($request->get('password'));
    }
    $user = $this->user_repository->update($data, $id);
    $user->roles()->sync($request->get('roles', []));
    $this->dispatcher->fire(new UserUpdatedEvent($user));
    return $user;
  }

  public function delete($id)
  {
    $user = $this->user_repository->with('roles')->find($id);
    $this->user_repository->delete($id);
    $this->dispatcher->fire(new UserDeletedEvent($user));
  }

  public function paginate($take = 5)
  {
    return $this->user_repository->paginate($take);
  }

}
