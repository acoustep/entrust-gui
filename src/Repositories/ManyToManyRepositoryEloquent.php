<?php

namespace Acoustep\EntrustGui\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Config;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
abstract class ManyToManyRepositoryEloquent extends BaseRepository implements RoleRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Config::get('entrust.'.$this->getModelName());
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Update attributes
     *
     * @param array $attributes
     * @param integer $id
     *
     * @return Model
     */
    public function update(array $attributes, $id)
    {
        $defaults = ["{$this->getRelationName()}" => []];
        $attributes = array_merge($defaults, $attributes);
        $model = parent::update($attributes, $id);
        $model->{$this->getShortRelationName()}()->sync($attributes["{$this->getRelationName()}"]);
        return $this->parserResult($model);
    }
}
