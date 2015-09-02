<?php

namespace Acoustep\EntrustGui\Repositories;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class RoleRepositoryEloquent extends ManyToManyRepositoryEloquent implements RoleRepository
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
        $defaults = ['permissions' => []];
        $attributes = array_merge($defaults, $attributes);
        $model = parent::update($attributes, $id);
        $model->perms()->sync($attributes['permissions']);
        return $this->parserResult($model);
    }
}
