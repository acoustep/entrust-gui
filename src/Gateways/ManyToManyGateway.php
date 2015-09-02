<?php namespace Acoustep\EntrustGui\Gateways;

use Prettus\Repository\Eloquent\BaseRepository;
use Acoustep\EntrustGui\Traits\PaginationGatewayTrait;
use Acoustep\EntrustGui\Traits\DeleteModelTrait;
use Acoustep\EntrustGui\Traits\FindModelTrait;
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

    use PaginationGatewayTrait, FindModelTrait, DeleteModelTrait;

    protected $repository;
    protected $config;
    protected $dispatcher;
    protected $event_created_class;
    protected $event_updated_class;
    protected $event_deleted_class;

    /**
     * Create a new gateway instance.
     *
     * @param Config $config
     * @param Repository $repository
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function __construct(Config $config, BaseRepository $repository, Dispatcher $dispatcher, $event_created_class, $event_updated_class, $event_deleted_class)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
        $this->event_created_class = $event_created_class;
        $this->event_updated_class = $event_updated_class;
        $this->event_deleted_class = $event_deleted_class;
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
        $model->{$this->getShortRelationName()}()->sync($request->get($this->getRelationName(), []));
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
        return $this->repository->with($this->getShortRelationName())->find($id);
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
        $model = $this->repository->update($request->all(), $id);
        $this->dispatcher->fire($this->event_updated_class->setModel($model));
        return $model;
    }

}
