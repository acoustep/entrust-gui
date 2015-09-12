<?php namespace Acoustep\EntrustGui\Traits;

trait GetRoleRelationNameTrait
{

    /**
     * Return relation name
     *
     *
     * @return string
     */
    public function getRelationName()
    {
        return 'permissions';
    }
    /**
     * Return relation short name
     *
     *
     * @return string
     */
    public function getShortRelationName()
    {
        return 'perms';
    }
}
