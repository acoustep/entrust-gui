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
class RoleCreatedEvent extends Event implements EventInterface
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
    public function __construct()
    {
    }

    public function setModel($model)
    {
        $this->role = $model;
        return $this;
    }
}
