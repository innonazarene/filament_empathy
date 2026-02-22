<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@empathy.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'online',
            'bio'      => 'Platform administrator.',
        ]);

        // Test listener
        User::create([
            'name'     => 'Jane Listener',
            'email'    => 'listener@empathy.com',
            'password' => Hash::make('password'),
            'role'     => 'listener',
            'status'   => 'online',
            'bio'      => "I'm here to listen and support you through whatever you're going through. You're not alone.",
        ]);

        // Test caller
        User::create([
            'name'     => 'John Caller',
            'email'    => 'caller@empathy.com',
            'password' => Hash::make('password'),
            'role'     => 'caller',
            'status'   => 'offline',
        ]);

        // Extra listeners for a richer demo
        $listenerBios = [
            'Certified counselor with 5 years of experience. I specialize in anxiety and stress management.',
            'Former social worker turned volunteer. I believe everyone deserves to be heard.',
            'Trained in active listening and emotional support. Here whenever you need to talk.',
            'Empathetic listener passionate about mental health awareness.',
        ];

        $listenerNames = ['Sarah Chen', 'Marcus Williams', 'Emma Rodriguez', 'David Kim'];

        foreach ($listenerNames as $i => $name) {
            User::create([
                'name'     => $name,
                'email'    => 'listener' . ($i + 2) . '@empathy.com',
                'password' => Hash::make('password'),
                'role'     => 'listener',
                'status'   => 'online',
                'bio'      => $listenerBios[$i],
            ]);
        }
    }
}
