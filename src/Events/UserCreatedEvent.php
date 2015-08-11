<?php namespace Acoustep\EntrustGui\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserCreatedEvent extends Event {

    use SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
      $this->user = $user;
    }

}
