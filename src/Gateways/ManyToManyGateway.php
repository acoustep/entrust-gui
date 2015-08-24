<?php namespace Acoustep\EntrustGui\Gateways;

use Acoustep\EntrustGui\Repositories\RoleRepository;
use Acoustep\EntrustGui\Events\RoleCreatedEvent;
use Acoustep\EntrustGui\Events\RoleUpdatedEvent;
use Acoustep\EntrustGui\Events\RoleDeletedEvent;
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
abstract class ManyToManyGateway
{

    use PaginationGatewayTrait;

    protected $repository;
    protected $config;
    protected $dispatcher;

}

