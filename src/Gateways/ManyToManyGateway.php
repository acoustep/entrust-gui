<?php namespace Acoustep\EntrustGui\Gateways;

use Prettus\Repository\Eloquent\BaseRepository;
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
    protected $model_name;
    protected $relation_name;
    protected $short_relation_name;
    protected $event_created_class;

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param Repository $repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, BaseRepository $repository, Dispatcher $dispatcher, $event_created_class, $model_name, $relation_name, $short_relation_name)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
        $this->model_name = $model_name;
        $this->event_created_class = $event_created_class;
        $this->relation_name = $relation_name;
        $this->short_relation_name = $short_relation_name;
    }

    /**
     * Create a model
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($request)
    {
        $model = $this->repository->create($request->all());
        $model->{$this->short_relation_name}()->sync($request->get($this->relation_name, []));
        $this->dispatcher->fire($this->event_created_class->setModel($model));
        return $model;
    }

    /**
     * Find model by ID
     *
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->repository->with($this->short_relation_name)->find($id);
    }

    /**
     * Update model
     *
     * @param Illuminate\Http\Request $request
     * @param integer $id
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($request, $id)
    {
        $model = $this->repository->find($id);
        $model->update($request->all());
        $model->{$this->short_relation_name}()->sync($request->get($this->relation_name, []));
        $event_class = '\Acoustep\EntrustGui\Events\\'.ucwords($this->model_name).'UpdatedEvent';
        $this->dispatcher->fire(new $event_class($model));
        return $model;
    }

    /**
     * Delete role
     *
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        $model = $this->repository->find($id);
        $this->repository->delete($id);
        $event_class = '\Acoustep\EntrustGui\Events\\'.ucwords($this->model_name).'DeletedEvent';
        $this->dispatcher->fire(new $event_class($model));
    }
}
