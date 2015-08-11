<?php namespace Acoustep\EntrustGui\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class PermissionUpdatedEvent extends Event {

    use SerializesModels;

    public $permission;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permission)
    {
      $this->permission = $permission;
    }

}


