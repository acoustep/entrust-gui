<?php namespace Acoustep\EntrustGui\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\User;
use Config;
use Log;
use Exception;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Config::get('auth.model');
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Create model
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        $defaults = ['roles' => []];
        $attributes = array_merge($defaults, $attributes);
        $model = parent::create($attributes);
        if (! in_array('Esensi\Model\Contracts\HashingModelInterface', class_implements($model))) {
            throw new Exception(
                "User model must implement Esensi\Model\Contracts\HashingModelInterface.
                Revert to 0.3.* or see upgrade guide for details."
            );
        }
        $model->roles()->sync($attributes['roles']);
        return $this->parserResult($model);
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
        $defaults = ['roles' => []];
        $attributes = array_merge($defaults, $attributes);
        $model = $this->find($id);
        if (! in_array('Esensi\Model\Contracts\HashingModelInterface', class_implements($model))) {
            throw new Exception(
                "User model must implement Esensi\Model\Contracts\HashingModelInterface. 
                Revert to 0.3.* or see upgrade guide for details."
            );
        }
        if (! array_key_exists('password', $attributes)) {
            $model->fill($attributes);
            if (Config::get('entrust-gui.confirmable') === true) {
                $model->password_confirmation = $model->password;
            }
            $model->saveWithoutHashing();
        } else {
            $model = parent::update($attributes, $id);
        }
        $model->roles()->sync($attributes['roles']);
        return $this->parserResult($model);
    }
}
