<?php namespace Acoustep\EntrustGui\Http\Controllers;

use Acoustep\EntrustGui\Gateways\PermissionGateway;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionsController extends ManyToManyController
{
    /**
     * Create a new PermissionsController instance.
     *
     * @param Request $request
     * @param Config $config
     * @param PermissionGateway $gateway
     *
     * @return void
     */
    public function __construct(Request $request, Config $config, PermissionGateway $model)
    {
        parent::__construct($request, $config, $model, 'permissions', 'role');
    }
}
