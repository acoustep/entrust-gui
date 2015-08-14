<?php namespace Acoustep\EntrustGui\Http\Controllers;

use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\PermissionGateway;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;
use Illuminate\Config\Repository as Config;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionsController extends Controller
{

    protected $request;
    protected $gateway;
    protected $role;
    protected $config;

    /**
     * Create a new PermissionsController instance.
     *
     * @param Request $request
     * @param PermissionGateway $gateway
     * @param Config $config
     *
     * @return void
     */
    public function __construct(Request $request, PermissionGateway $gateway, Config $config)
    {
        $this->config = $config;
        $this->request = $request;
        $this->gateway = $gateway;
        $role_class = $this->config->get('entrust.role');
        $this->role = new $role_class;
    }

    /**
     * Display a listing of the resource.
     * GET /permissions
     *
     * @return Response
     */
    public function index()
    {
        $permissions = $this->gateway->paginate($this->config->get('entrust-gui.pagination.permissions'));

        return view(
            'entrust-gui::permissions.index',
            compact(
                'permissions'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     * GET /permissions/create
     *
     * @return Response
     */
    public function create()
    {
          $permission_class = $this->config->get('entrust.permission');
          $permission = new $permission_class;
          $roles = $this->role->lists('name', 'id');

          return view(
              'entrust-gui::permissions.create',
              compact(
                  'permission',
                  'roles'
              )
          );
    }

    /**
     * Store a newly created resource in storage.
     * POST /permissions
     *
     * @return Response
     */
    public function store()
    {
        try {
            $this->gateway->create($this->request);
        } catch (ValidationException $e) {
            return back()->withErrors($e->getErrors());
        }
        return redirect(route('entrust-gui::permissions.index'))
          ->withSuccess(trans('entrust-gui::permissions.created'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /permissions/{id}/edit
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $permission = $this->gateway->find($id);
        $roles = $this->role->lists('name', 'id');

        return view(
            'entrust-gui::permissions.edit',
            compact(
                'permission',
                'roles'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * PUT /permissions/{id}
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        try {
            $permission = $this->gateway->update($this->request, $id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->getErrors());
        }
        return redirect(route('entrust-gui::permissions.index'))
          ->withSuccess(trans('entrust-gui::permissions.updated'));
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /permissions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->gateway->delete($id);
        return redirect(route('entrust-gui::permissions.index'))
          ->withSuccess(trans('entrust-gui::permissions.destroyed'));
    }
}
