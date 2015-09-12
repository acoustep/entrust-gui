<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\RoleRepository;
use Acoustep\EntrustGui\Traits\GetRoleModelNameTrait;
use Acoustep\EntrustGui\Traits\GetRoleRelationNameTrait;
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

    use GetRoleModelNameTrait, GetRoleRelationNameTrait;

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param RoleRepository $repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, RoleRepository $repository, Dispatcher $dispatcher)
    {
        parent::__construct($config, $repository, $dispatcher);
    }
}
