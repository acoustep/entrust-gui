<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\UserRepository;
use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Acoustep\EntrustGui\Events\UserUpdatedEvent;
use Acoustep\EntrustGui\Events\UserDeletedEvent;
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

    protected $user_repository;
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
    public function __construct(Config $config, UserRepository $user_repository, Dispatcher $dispatcher, Hasher $hash)
    {
        $this->config = $config;
        $this->user_repository = $user_repository;
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
        $user = $this->user_repository->create($data);
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
        return $this->user_repository->with('roles')->find($id);
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
        $user = $this->user_repository->update($data, $id);
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
        $user = $this->user_repository->with('roles')->find($id);
        $this->user_repository->delete($id);
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
        return $this->user_repository->paginate($take);
    }
}
