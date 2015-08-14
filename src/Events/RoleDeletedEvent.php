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
class RoleDeletedEvent extends Event
{

    use SerializesModels;

    public $role;
    /**
     * Create a new event instance.
     *
     * @param $role
     *
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }
}
