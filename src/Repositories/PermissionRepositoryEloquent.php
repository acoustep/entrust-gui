<?php

namespace Acoustep\EntrustGui\Repositories;

use Acoustep\EntrustGui\Traits\GetPermissionModelNameTrait;
use Acoustep\EntrustGui\Traits\GetPermissionRelationNameTrait;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionRepositoryEloquent extends ManyToManyRepositoryEloquent implements PermissionRepository
{

    use GetPermissionModelNameTrait, GetPermissionRelationNameTrait;

}
