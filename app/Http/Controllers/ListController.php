<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListController extends Controller
{
    public function index(): Response
    {
        $listeners = User::where('role', 'listener')
            ->where('status', 'online')
            ->select('id', 'name', 'bio', 'avatar', 'status')
            ->get()
            ->map(fn($l) => [
                'id'         => $l->id,
                'name'       => $l->name,
                'bio'        => $l->bio,
                'status'     => $l->status,
                'avatar_url' => $l->avatar_url,
            ]);

        return Inertia::render('Listeners/Index', [
            'listeners' => $listeners,
        ]);
    }
}
