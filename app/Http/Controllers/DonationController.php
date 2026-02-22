<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DonationController extends Controller
{
    public function create(Call $call): Response|RedirectResponse
    {
        // Only the caller for this call can donate
        if (auth()->id() !== $call->caller_id) {
            abort(403);
        }

        $call->load('listener:id,name,avatar,bio');

        return Inertia::render('Donate/Create', [
            'call' => [
                'id'       => $call->id,
                'status'   => $call->status,
                'duration' => $call->duration,
                'listener' => [
                    'id'         => $call->listener->id,
                    'name'       => $call->listener->name,
                    'avatar_url' => $call->listener->avatar_url,
                    'bio'        => $call->listener->bio,
                ],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'call_id' => 'required|exists:calls,id',
            'amount'  => 'required|numeric|min:0.50|max:1000',
            'message' => 'nullable|string|max:500',
        ]);

        $call = Call::findOrFail($request->call_id);

        if (auth()->id() !== $call->caller_id) {
            abort(403);
        }

        Donation::create([
            'call_id'     => $call->id,
            'caller_id'   => auth()->id(),
            'listener_id' => $call->listener_id,
            'amount'      => $request->amount,
            'message'     => $request->message,
        ]);

        // Add to listener's balance
        $call->listener->increment('balance', $request->amount);

        return redirect()->route('listeners.index')
            ->with('success', 'Thank you for your donation!');
    }
}
