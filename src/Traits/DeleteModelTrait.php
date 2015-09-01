<?php namespace Acoustep\EntrustGui\Traits;

trait DeleteModelTrait
{

    /**
     * Delete model
     *
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        $model = $this->repository->find($id);
        $this->repository->delete($id);
        $this->dispatcher->fire($this->event_deleted_class->setModel($model));
    }

}

