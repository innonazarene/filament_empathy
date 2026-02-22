<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_users'        => User::count(),
            'online_listeners'   => User::where('role', 'listener')->where('status', 'online')->count(),
            'active_calls'       => Call::where('status', 'active')->count(),
            'total_donations'    => (float) Donation::sum('amount'),
        ];

        $calls = Call::with([
            'caller:id,name',
            'listener:id,name',
        ])->latest()->take(50)->get()->map(fn($c) => [
            'id'         => $c->id,
            'status'     => $c->status,
            'duration'   => $c->duration,
            'created_at' => $c->created_at->format('M d, Y H:i'),
            'caller'     => ['name' => $c->caller->name],
            'listener'   => ['name' => $c->listener->name],
        ]);

        $donations = Donation::with([
            'caller:id,name',
            'listener:id,name',
            'call:id',
        ])->latest()->take(50)->get()->map(fn($d) => [
            'id'         => $d->id,
            'amount'     => $d->amount,
            'message'    => $d->message,
            'created_at' => $d->created_at->format('M d, Y H:i'),
            'caller'     => ['name' => $d->caller->name],
            'listener'   => ['name' => $d->listener->name],
            'call_id'    => $d->call_id,
        ]);

        $users = User::latest()->get()->map(fn($u) => [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'role'       => $u->role,
            'status'     => $u->status,
            'avatar_url' => $u->avatar_url,
            'balance'    => $u->balance,
            'created_at' => $u->created_at->format('M d, Y'),
        ]);

        return Inertia::render('Admin/Dashboard', [
            'stats'     => $stats,
            'calls'     => $calls,
            'donations' => $donations,
            'users'     => $users,
        ]);
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
