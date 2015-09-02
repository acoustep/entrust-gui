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
class RoleGateway extends ManyToManyGateway implements ManyToManyGatewayInterface
{

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param RoleRepository $repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, RoleRepository $repository, Dispatcher $dispatcher, RoleCreatedEvent $event_created_class, RoleUpdatedEvent $event_updated_class, RoleDeletedEvent $event_deleted_class)
    {
        parent::__construct($config, $repository, $dispatcher, $event_created_class, $event_updated_class, $event_deleted_class);
    }

    public function getModelName()
    {
        return 'role';
    }

    public function getShortRelationName()
    {
        return 'perms';
    }

    public function getRelationName()
    {
        return 'permissions';
    }

}
