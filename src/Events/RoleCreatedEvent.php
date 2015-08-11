<?php namespace Acoustep\EntrustGui\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleCreatedEvent extends Event {

    use SerializesModels;

    public $role;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($role)
    {
      $this->role = $role;
    }

}
