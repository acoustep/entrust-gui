<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\UserRepository;
use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Acoustep\EntrustGui\Events\UserUpdatedEvent;
use Acoustep\EntrustGui\Events\UserDeletedEvent;
use Acoustep\EntrustGui\Traits\PaginationGatewayTrait;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class UserGateway
{

    use PaginationGatewayTrait;

    protected $repository;
    protected $role;
    protected $config;
    protected $dispatcher;
    protected $hash;

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param UserRepository $permission_repository
     * @param Dispatcher $dispatcher
     * @param Hasher $hash
     *
     * @return void
     */
    public function __construct(Config $config, UserRepository $repository, Dispatcher $dispatcher, Hasher $hash)
    {
        $this->config = $config;
        $this->repository = $repository;
        $role_class = $this->config->get('entrust.role');
        $this->role = new $role_class;
        $this->dispatcher = $dispatcher;
        $this->hash = $hash;
    }

    /**
     * Create a user
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($request)
    {
        $data = $request->except('password');
        $data['password'] = ($request->get('password', false)) ? $this->hash->make($request->get('password', '')) : '';
        $user = $this->repository->create($data);
        $user->roles()->sync($request->get('roles', []));

        $this->dispatcher->fire(new UserCreatedEvent($user));
        return $user;
    }

    /**
     * Find user by ID
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
     * Update user
     *
     * @param Illuminate\Http\Request $request
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($request, $id)
    {
        $data = $request->except('password');
        if ($request->get('password', false)) {
            $data['password'] = $this->hash->make($request->get('password'));
        }
        $user = $this->repository->update($data, $id);
        $user->roles()->sync($request->get('roles', []));
        $this->dispatcher->fire(new UserUpdatedEvent($user));
        return $user;
    }

    /**
     * Delete user
     *
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        $user = $this->repository->with('roles')->find($id);
        $this->repository->delete($id);
        $this->dispatcher->fire(new UserDeletedEvent($user));
    }

    /**
     * Paginate users
     *
     * @param integer $take
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($take = 5)
    {
        return $this->repository->paginate($take);
    }
}
