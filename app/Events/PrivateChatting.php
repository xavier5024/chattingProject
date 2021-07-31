<?php

namespace App\Events;

use Illuminate\Support\Facades\Auth;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PrivateChatting implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $channel_name;

    public function __construct($channel_name)
    {
        //
        $this->channel_name = $channel_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('monitoring.'.$this->channel_name);
    }

    public function broadcastWith()
    {
        $user = Auth::user();
        $data = request()->all();
        $data["type"] = "message";
        return [
            "data"   => $data,
            "user"	=> $user
          ];
    }
}
