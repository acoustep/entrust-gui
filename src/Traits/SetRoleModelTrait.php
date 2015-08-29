<?php namespace Acoustep\EntrustGui\Traits;

trait SetRoleModelTrait
{

    public $role;

    /**
     * Create a new event instance.
     *
     * @param $role
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->role = $model;
        return $this;
    }
}
