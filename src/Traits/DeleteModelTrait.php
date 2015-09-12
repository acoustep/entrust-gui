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
        $event_class = "Acoustep\EntrustGui\Events\\".ucwords($this->getModelName()).'DeletedEvent';
        $event = new $event_class;
        $this->dispatcher->fire($event->setModel($model));
    }
}
