<?php namespace Acoustep\EntrustGui\Gateways;

use Hash;
use Illuminate\Config\Repository as Config;
use Acoustep\EntrustGui\Repositories\RoleRepository;

class RoleGateway {

  protected $role_repository;
  protected $config;

  public function __construct(Config $config, RoleRepository $role_repository)
  {
    $this->config = $config;
    $this->role_repository = $role_repository;
  }

  public function create($request)
  {
    return $this->role_repository->create($request->all());
  }

  public function find($id)
  {
    return $this->role_repository->find($id);
  }

  public function update($request, $id)
  {
    $role = $this->role_repository->find($id);
    $role->update($request->all());
    return $role;
  }

  public function delete($id)
  {
    $this->role_repository->delete($id);
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

