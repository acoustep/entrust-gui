<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\PermissionRepository;
use Acoustep\EntrustGui\Events\PermissionCreatedEvent;
use Acoustep\EntrustGui\Events\PermissionUpdatedEvent;
use Acoustep\EntrustGui\Events\PermissionDeletedEvent;
use Acoustep\EntrustGui\Traits\PaginationGatewayTrait;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionGateway
{

    use PaginationGatewayTrait;

    protected $repository;
    protected $config;
    protected $dispatcher;

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param PermissionRepository $repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, PermissionRepository $repository, Dispatcher $dispatcher)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create a permission
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($request)
    {
        $permission = $this->repository->create($request->all());
        $permission->roles()->sync($request->get('roles', []));
        $this->dispatcher->fire(new PermissionCreatedEvent($permission));
        return $permission;
    }

    /**
     * Find permission by ID
     *
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->repository->with('roles')->find($id);
    }

    /**
     * Update permission
     *
     * @param Illuminate\Http\Request $request
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($request, $id)
    {
        $permission = $this->repository->find($id);
        $permission->update($request->all());
        $permission->roles()->sync($request->get('roles', []));
        $this->dispatcher->fire(new PermissionUpdatedEvent($permission));
        return $permission;
    }

    /**
     * Delete permission
     *
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        $permission = $this->repository->find($id);
        $this->repository->delete($id);
        $this->dispatcher->fire(new PermissionDeletedEvent($permission));
    }

}
