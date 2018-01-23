<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImagesCompared
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @array
     */
    public $output;

    /**
     * @string
     */
    public $before;

    /**
     * @string
     */
    public $after;

    /**
     * Create a new event instance.
     *
     * @param string $before
     * @param string $after
     * @param array $output
     */
    public function __construct($before, $after, array $output)
    {
        $this->output = $output;
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
