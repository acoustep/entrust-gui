<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\RoleRepository;
use Acoustep\EntrustGui\Events\RoleCreatedEvent;
use Acoustep\EntrustGui\Events\RoleUpdatedEvent;
use Acoustep\EntrustGui\Events\RoleDeletedEvent;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class RoleGateway
{

    protected $role_repository;
    protected $config;
    protected $dispatcher;
    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param RoleRepository $role_repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, RoleRepository $role_repository, Dispatcher $dispatcher)
    {
        $this->config = $config;
        $this->role_repository = $role_repository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create a role
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($request)
    {
        $role = $this->role_repository->create($request->all());
        $role->perms()->sync($request->get('permissions', []));
        $this->dispatcher->fire(new RoleCreatedEvent($role));
        return $role;
    }

    /**
     * Find role by ID
     *
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->role_repository->with('perms')->find($id);
    }

    /**
     * Update role
     *
     * @param Illuminate\Http\Request $request
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($request, $id)
    {
        $role = $this->role_repository->find($id);
        $role->update($request->all());
        $role->perms()->sync($request->get('permissions', []));
        $this->dispatcher->fire(new RoleUpdatedEvent($role));
        return $role;
    }

    /**
     * Delete role
     *
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        $role = $this->role_repository->find($id);
        $this->role_repository->delete($id);
        $this->dispatcher->fire(new RoleDeletedEvent($role));
    }

    /**
     * Paginate roles
     *
     * @param integer $take
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($take = 5)
    {
        return $this->role_repository->paginate($take);
    }
}
