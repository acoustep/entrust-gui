<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\UserRepository;
use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Acoustep\EntrustGui\Events\UserUpdatedEvent;
use Acoustep\EntrustGui\Events\UserDeletedEvent;
use Acoustep\EntrustGui\Traits\PaginationGatewayTrait;
use Acoustep\EntrustGui\Traits\DeleteModelTrait;
use Acoustep\EntrustGui\Traits\FindModelTrait;
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

    use PaginationGatewayTrait, FindModelTrait, DeleteModelTrait;

    protected $repository;
    protected $role;
    protected $config;
    protected $dispatcher;
    protected $hash;
    protected $short_relation_name;
    protected $event_created_class;
    protected $event_updated_class;
    protected $event_deleted_class;

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
    public function __construct(Config $config, UserRepository $repository, Dispatcher $dispatcher, Hasher $hash, UserCreatedEvent $event_created_class, UserUpdatedEvent $event_updated_class, UserDeletedEvent $event_deleted_class)
    {
        $this->config = $config;
        $this->repository = $repository;
        $role_class = $this->config->get('entrust.role');
        $this->role = new $role_class;
        $this->dispatcher = $dispatcher;
        $this->hash = $hash;
        $this->event_created_class = $event_created_class;
        $this->event_updated_class = $event_updated_class;
        $this->event_deleted_class = $event_deleted_class;
        $this->short_relation_name = 'roles';
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

        $this->dispatcher->fire($this->event_created_class->setModel($user));
        return $user;
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
        $this->dispatcher->fire($this->event_updated_class->setModel($user));
        return $user;
    }


}
