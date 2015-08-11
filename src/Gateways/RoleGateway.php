<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\RoleRepository;
use Acoustep\EntrustGui\Events\RoleCreatedEvent;
use Acoustep\EntrustGui\Events\RoleUpdatedEvent;
use Acoustep\EntrustGui\Events\RoleDeletedEvent;
use Hash;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

class RoleGateway {

  protected $role_repository;
  protected $config;
  protected $dispatcher;

  public function __construct(Config $config, RoleRepository $role_repository, Dispatcher $dispatcher)
  {
    $this->config = $config;
    $this->role_repository = $role_repository;
    $this->dispatcher = $dispatcher;
  }

  public function create($request)
  {
    $role = $this->role_repository->create($request->all());
    $this->dispatcher->fire(new RoleCreatedEvent($role));
    return $role;
  }

  public function find($id)
  {
    return $this->role_repository->find($id);
  }

  public function update($request, $id)
  {
    $role = $this->role_repository->find($id);
    $role->update($request->all());
    $this->dispatcher->fire(new RoleUpdatedEvent($role));
    return $role;
  }

  public function delete($id)
  {
    $role = $this->role_repository->find($id);
    $this->role_repository->delete($id);
    $this->dispatcher->fire(new RoleDeletedEvent($role));
  }

  public function paginate($take = 5)
  {
    return $this->role_repository->paginate($take);
  }

  public function newRoleInstance()
  {
    $role_class = $this->config->get('entrust.role');

    return new $role_class;
  }

}

