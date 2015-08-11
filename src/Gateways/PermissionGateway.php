<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\PermissionRepository;
use Acoustep\EntrustGui\Events\PermissionCreatedEvent;
use Acoustep\EntrustGui\Events\PermissionUpdatedEvent;
use Acoustep\EntrustGui\Events\PermissionDeletedEvent;
use Hash;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

class PermissionGateway {

  protected $permission_repository;
  protected $config;
  protected $dispatcher;

  public function __construct(Config $config, PermissionRepository $permission_repository, Dispatcher $dispatcher)
  {
    $this->config = $config;
    $this->permission_repository = $permission_repository;
    $this->dispatcher = $dispatcher;
  }

  public function create($request)
  {
    $permission = $this->permission_repository->create($request->all());
    $this->dispatcher->fire(new PermissionCreatedEvent($permission));
    return $permission;
  }

  public function find($id)
  {
    return $this->permission_repository->find($id);
  }

  public function update($request, $id)
  {
    $permission = $this->permission_repository->find($id);
    $permission->update($request->all());
    $this->dispatcher->fire(new PermissionUpdatedEvent($permission));
    return $permission;
  }

  public function delete($id)
  {
    $permission = $this->permission_repository->find($id);
    $this->permission_repository->delete($id);
    $this->dispatcher->fire(new PermissionDeletedEvent($permission));
  }

  public function paginate($take = 5)
  {
    return $this->permission_repository->paginate($take);
  }

  public function newPermissionInstance()
  {
    $permission_class = $this->config->get('entrust.permission');

    return new $permission_class;
  }

}


