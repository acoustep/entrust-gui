<?php

namespace Acoustep\EntrustGui\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
interface RoleRepository extends RepositoryInterface
{

    public function getModelName();

    public function getRelationName();

    public function getShortRelationName();
}
