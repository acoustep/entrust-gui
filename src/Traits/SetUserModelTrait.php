<?php namespace Acoustep\EntrustGui\Traits;

trait SetUserModelTrait
{

    public $user;

    /**
     * Create a new event instance.
     *
     * @param $user
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->user = $model;
        return $this;
    }
}
