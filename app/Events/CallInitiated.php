<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Call $call
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('listener.' . $this->call->listener_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call.initiated';
    }

    public function broadcastWith(): array
    {
        return [
            'call_id' => $this->call->id,
            'caller' => [
                'id' => $this->call->caller->id,
                'name' => $this->call->caller->name,
                'avatar_url' => $this->call->caller->avatar_url,
            ],
        ];
    }
}
