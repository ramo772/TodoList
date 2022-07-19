<?php

namespace App\Events;

use App\Http\Misc\Helpers\Success;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Http\Misc\Traits\WebServiceResponse;
use App\Http\Resources\TodosResource;
use App\Models\Todo;

class TodoUpdate implements ShouldBroadcast
{
    use WebServiceResponse;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $todo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->todo = Todo::orderBy('status')->get();

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('Todo');
    }
}
