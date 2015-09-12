<?php namespace Acoustep\EntrustGui\Traits;

trait GetPermissionUserRelationNameTrait
{

    /**
     * Return relation name
     *
     *
     * @return string
     */
    public function getRelationName()
    {
        return 'roles';
    }

    /**
     * Return relation short name
     *
     *
     * @return string
     */
    public function getShortRelationName()
    {
        return 'roles';
    }
}
