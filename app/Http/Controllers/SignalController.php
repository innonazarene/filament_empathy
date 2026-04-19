<?php

namespace App\Http\Controllers;

use App\Events\WebRtcSignal;
use App\Models\Call;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SignalController extends Controller
{
    public function send(Request $request, Call $call): JsonResponse
    {
        $request->validate([
            'type'   => 'required|in:offer,answer,ice-candidate',
            'signal' => 'required',
        ]);

        $user = auth()->user();

        // Only call participants can send signals
        if ($user->id !== $call->caller_id && $user->id !== $call->listener_id) {
            abort(403);
        }

        broadcast(new WebRtcSignal(
            callId: $call->id,
            type: $request->type,
            signal: $request->signal,
            fromUserId: $user->id
        ));

        return response()->json(['ok' => true]);
    }
}
