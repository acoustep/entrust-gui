<?php

namespace Acoustep\EntrustGui\Repositories;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionRepositoryEloquent extends ManyToManyRepositoryEloquent implements PermissionRepository
{

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
        $defaults = ['roles' => []];
        $attributes = array_merge($defaults, $attributes);
        $model = parent::update($attributes, $id);
        $model->roles()->sync($attributes['roles']);
        return $this->parserResult($model);
    }
}
