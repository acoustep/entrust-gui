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
     * Paginate models
     *
     * @param integer $take
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($take = 5, $search = "")
    {
	    $search = trim($search);
	    return $this->repository->scopeQuery(function($query) use ($search) {
		    return ($search != "") ? $query->where('name', 'LIKE', '%'.$search.'%') : $query;
	    })->paginate($take);
    }
}
