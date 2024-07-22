<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class OnlineListener extends Component
{
    public $onlineUsers = [];
    public $userModel;

    public function mount()
    {
        $this->userModel  = new User;
        $this->updateOnlineUsers();
    }
    public function updateOnlineUsers()
    {
        $this->onlineUsers = $this->userModel->getActiveUsersInLastMinutes(2);
        if(count($this->onlineUsers) > 0) {
            $this->onlineUsers = $this->onlineUsers->map(function ($user) {
                $user->custom_fields = json_decode($user->custom_fields);
                return $user;
            });
        }
    }
    public function render()
    {
        return view('livewire.online-listener');
    }
}
