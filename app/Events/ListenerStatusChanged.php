<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListenerStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $listener
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('listeners'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'listener.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->listener->id,
            'name' => $this->listener->name,
            'status' => $this->listener->status,
            'bio' => $this->listener->bio,
            'avatar_url' => $this->listener->avatar_url,
        ];
    }
}
