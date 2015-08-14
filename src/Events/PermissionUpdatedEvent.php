<?php namespace Acoustep\EntrustGui\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class PermissionUpdatedEvent extends Event
{

    use SerializesModels;

    public $permission;

    /**
     * Create a new event instance.
     *
     * @param $permission
     *
     * @return void
     */    public function __construct($permission)
    {
        $this->permission = $permission;
}
}
