<?php namespace Acoustep\EntrustGui\Http\Controllers;

use Acoustep\EntrustGui\Gateways\RoleGateway;
use App\Http\Controllers\Controller;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class RolesController extends ManyToManyController
{
    /**
     * Create a new RolesController instance.
     *
     * @param Request $request
     * @param RoleGateway $gateway
     * @param Config $config
     *
     * @return void
     */
    public function __construct(Request $request, Config $config, RoleGateway $model) {
        parent::__construct($request, $config, $model, 'roles', 'permission');
    }
}
