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
class UserDeletedEvent extends Event
{

    use SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     *
     * @param $user
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
