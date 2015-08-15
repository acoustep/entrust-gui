<?php namespace Acoustep\EntrustGui\Http\Controllers;

use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\RoleGateway;
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
class RolesController extends Controller
{

    protected $request;
    protected $gateway;
    protected $permission;
    protected $config;

    /**
     * Create a new RolesController instance.
     *
     * @param Request $request
     * @param PermissionGateway $gateway
     * @param Config $config
     *
     * @return void
     */
    public function __construct(Request $request, RoleGateway $gateway, Config $config)
    {
        $this->config = $config;
        $this->request = $request;
        $this->gateway = $gateway;
        $permission_class = $this->config->get('entrust.permission');
        $this->permission = new $permission_class;
    }

    /**
     * Display a listing of the resource.
     * GET /roles
     *
     * @return Response
     */
    public function index()
    {
        $roles = $this->gateway->paginate($this->config->get('entrust-gui.pagination.roles'));

        return view('entrust-gui::roles.index', compact(
            'roles'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * GET /roles/create
     *
     * @return Response
     */
    public function create()
    {
        $role_class = $this->config->get('entrust.role');
        $role = new $role_class;
        $permissions = $this->permission->lists('name', 'id');

        return view('entrust-gui::roles.create', compact(
            'role',
            'permissions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * POST /roles
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
        return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.created'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /roles/{id}/edit
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $role = $this->gateway->find($id);
        $permissions = $this->permission->lists('name', 'id');

        return view('entrust-gui::roles.edit', compact(
            'role',
            'permissions'
        ));
    }

    /**
     * Update the specified resource in storage.
     * PUT /roles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        try {
            $role = $this->gateway->update($this->request, $id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->getErrors());
        }
        return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.updated'));
    }
  
    /**
     * Remove the specified resource from storage.
     * DELETE /roles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->gateway->delete($id);
        return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.destroyed'));
    }
}
