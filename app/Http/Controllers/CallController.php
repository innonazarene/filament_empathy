<?php

namespace App\Http\Controllers;

use App\Events\CallEnded;
use App\Events\CallInitiated;
use App\Models\Call;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CallController extends Controller
{
    public function show(Call $call): Response
    {
        $call->load(['caller:id,name,avatar', 'listener:id,name,avatar']);

        // Ensure only participants can view the call
        $user = auth()->user();
        if ($user->id !== $call->caller_id && $user->id !== $call->listener_id) {
            abort(403);
        }

        return Inertia::render('Call/Show', [
            'call' => [
                'id'          => $call->id,
                'status'      => $call->status,
                'caller_id'   => $call->caller_id,
                'listener_id' => $call->listener_id,
                'caller'      => [
                    'id'         => $call->caller->id,
                    'name'       => $call->caller->name,
                    'avatar_url' => $call->caller->avatar_url,
                ],
                'listener'    => [
                    'id'         => $call->listener->id,
                    'name'       => $call->listener->name,
                    'avatar_url' => $call->listener->avatar_url,
                ],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'listener_id' => 'required|exists:users,id',
        ]);

        $listener = User::findOrFail($request->listener_id);

        if ($listener->status !== 'online') {
            return back()->with('error', 'This listener is not available right now.');
        }

        // Mark listener as busy
        $listener->update(['status' => 'busy']);

        $call = Call::create([
            'caller_id'   => auth()->id(),
            'listener_id' => $request->listener_id,
            'status'      => 'pending',
        ]);

        // Notify the listener
        broadcast(new CallInitiated($call->load('caller')));

        return redirect()->route('calls.show', $call->id);
    }

    public function end(Call $call): RedirectResponse
    {
        $user = auth()->user();
        if ($user->id !== $call->caller_id && $user->id !== $call->listener_id) {
            abort(403);
        }

        $call->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);

        // Free up the listener
        $call->listener->update(['status' => 'online']);

        broadcast(new CallEnded($call->id));

        // Callers go to donation, listeners go to dashboard
        if ($user->id === $call->caller_id) {
            return redirect()->route('donations.create', $call->id);
        }

        return redirect()->route('listener.dashboard');
    }
}
