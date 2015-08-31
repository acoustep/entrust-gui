<?php namespace Acoustep\EntrustGui\Traits;

trait FindModelTrait
{

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

}
