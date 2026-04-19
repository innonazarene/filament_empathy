<?php

namespace App\Http\Controllers;

use App\Events\ListenerStatusChanged;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListenerController extends Controller
{
    public function dashboard(): Response
    {
        $user = auth()->user();

        $donations = Donation::where('listener_id', $user->id)
            ->with(['caller:id,name,avatar', 'call:id,started_at,ended_at'])
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id'         => $d->id,
                'amount'     => $d->amount,
                'message'    => $d->message,
                'created_at' => $d->created_at->format('M d, Y'),
                'caller'     => [
                    'id'         => $d->caller->id,
                    'name'       => $d->caller->name,
                    'avatar_url' => $d->caller->avatar_url,
                ],
                'call' => [
                    'id'       => $d->call->id,
                    'duration' => $d->call->duration,
                ],
            ]);

        return Inertia::render('Listener/Dashboard', [
            'donations' => $donations,
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:online,offline,busy',
        ]);

        $user = auth()->user();
        $user->update(['status' => $request->status]);

        broadcast(new ListenerStatusChanged($user->fresh()));

        return response()->json([
            'status' => $user->status,
            'message' => 'Status updated successfully.',
        ]);
    }
}
