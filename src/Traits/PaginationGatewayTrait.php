<?php namespace Acoustep\EntrustGui\Traits;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
trait PaginationGatewayTrait
{

    /**
     * Paginate permissions
     *
     * @param integer $take
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($take = 5)
    {
        return $this->repository->paginate($take);
    }
}
