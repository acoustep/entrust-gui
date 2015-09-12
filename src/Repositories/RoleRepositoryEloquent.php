<?php

namespace Acoustep\EntrustGui\Repositories;

use Acoustep\EntrustGui\Traits\GetRoleModelNameTrait;
use Acoustep\EntrustGui\Traits\GetRoleRelationNameTrait;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class RoleRepositoryEloquent extends ManyToManyRepositoryEloquent implements RoleRepository
{

    use GetRoleModelNameTrait, GetRoleRelationNameTrait;
}
