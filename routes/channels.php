<?php

use App\Models\Call;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for each listener's incoming call notifications
Broadcast::channel('listener.{listenerId}', function ($user, $listenerId) {
    return (int) $user->id === (int) $listenerId;
});

// Private channel for each active call — both participants can join
Broadcast::channel('call.{callId}', function ($user, $callId) {
    $call = Call::find($callId);
    if (! $call) {
        return false;
    }
    return (int) $user->id === (int) $call->caller_id
        || (int) $user->id === (int) $call->listener_id;
});
