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
abstract class ManyToManyController extends Controller
{

    protected $request;
    protected $gateway;
    protected $relation;
    protected $config;
    protected $resource;
    protected $relation_name;
    protected $short_relation_name;

    /**
     * Create a new ManyToManyController instance.
     *
     * @param Request $request
     * @param PermissionGateway $gateway
     * @param Config $config
     *
     * @return void
     */
    public function __construct(Request $request, Config $config, $gateway, $resource, $relation, $short_relation_name)
    {
        $this->config = $config;
        $this->request = $request;
        $this->gateway = $gateway;
        $relation_class = $this->config->get('entrust.'.$relation);
        $this->relation = new $relation_class;
        $this->resource = $resource;
        $this->relation_name = "{$relation}";
        $this->short_relation_name = $short_relation_name;
    }

    /**
     * Display a listing of the resource.
     * GET /roles
     *
     * @return Response
     */
    public function index()
    {
        $models = $this->gateway->paginate($this->config->get('entrust-gui.pagination.'.$this->resource));
        $resource = $this->resource;

        return view('entrust-gui::'.$this->resource.'.index', compact(
            "models",
            "resource"
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
        $model_class = $this->config->get('entrust.'.str_singular($this->resource));
        $model = new $model_class;
        $resource = $this->resource;
        $relations = $this->relation->lists('name', 'id');
        $relation_name = $this->relation_name;
        $short_relation_name = $this->short_relation_name;

        return view('entrust-gui::'.$this->resource.'.create', compact(
            'model',
            'resource',
            'relation_name',
            'relations',
            'short_relation_name'
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
        return redirect(route('entrust-gui::'.$this->resource.'.index'))->withSuccess(trans('entrust-gui::'.$this->resource.'.created'));
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
        $model = $this->gateway->find($id);
        $resource = $this->resource;
        $relations = $this->relation->lists('name', 'id');
        $relation_name = $this->relation_name;
        $short_relation_name = $this->short_relation_name;

        return view('entrust-gui::'.$this->resource.'.edit', compact(
            'model',
            'resource',
            'relation_name',
            'relations',
            'short_relation_name'
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
        return redirect(route('entrust-gui::'.$this->resource.'.index'))->withSuccess(trans('entrust-gui::'.$this->resource.'.updated'));
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
        return redirect(route('entrust-gui::'.$this->resource.'.index'))->withSuccess(trans('entrust-gui::'.$this->resource.'.destroyed'));
    }
}
