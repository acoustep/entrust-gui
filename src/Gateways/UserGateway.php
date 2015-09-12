<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\UserRepository;
use Acoustep\EntrustGui\Events\UserCreatedEvent;
use Acoustep\EntrustGui\Events\UserUpdatedEvent;
use Acoustep\EntrustGui\Events\UserDeletedEvent;
use Acoustep\EntrustGui\Traits\DeleteModelTrait;
use Acoustep\EntrustGui\Traits\FindModelTrait;
use Acoustep\EntrustGui\Traits\GetPermissionUserRelationNameTrait;
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
class UserGateway implements ManyToManyGatewayInterface
{

    use PaginationGatewayTrait, FindModelTrait, DeleteModelTrait, GetPermissionUserRelationNameTrait;

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
        $data = $request->all();
        $user = $this->repository->create($data);

        $event_class = "Acoustep\EntrustGui\Events\\".ucwords($this->getModelName()).'CreatedEvent';
        $event = new $event_class;
        $this->dispatcher->fire($event->setModel($user));
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
        $data = $request->except('password', 'password_confirmation');
        if ($request->has('password')) {
            $data['password'] = $request->get('password');
            $data['password_confirmation'] = $request->get('password_confirmation');
        }
        $user = $this->repository->update($data, $id);
        $event_class = "Acoustep\EntrustGui\Events\\".ucwords($this->getModelName()).'UpdatedEvent';
        $event = new $event_class;
        $this->dispatcher->fire($event->setModel($user));
        return $user;
    }

    /**
     * Return model name
     *
     *
     * @return string
     */
    public function getModelName()
    {
        return 'user';
    }
}
