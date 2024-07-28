<?php

namespace App\Livewire;

use App\Models\CurrentVideoSession;
use App\Models\User;
use Livewire\Component;

class OnlineListener extends Component
{
    public $onlineUsers = [];
    public $modalOpen;
    public $userModel;
    public $user_id;
    public $offer;
    public $process;
    public $answer;

    public function mount()
    {
        $this->process = true;
        $this->modalOpen = false;
        $this->userModel  = new User;
        $this->user_id = null;
        $this->offer = null;
        $this->answer = null;
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
    public function createOfferSession($offer, $id)
    {
        if(!$this->user_id){
            $this->user_id = $id;
        }
        CurrentVideoSession::where('user_id', $id)->delete();
        CurrentVideoSession::create([
            'user_id' => $id,
            'guest_peer_connection' => $offer,
            'remarks' => 'incomming call'
        ]);
        $this->offer = $offer;
    }
    public function checkIfUserPeerExist()
    {
        if($this->user_id) {
            $userPeerConnection = CurrentVideoSession::where(['user_id'=> $this->user_id])->orderBy('created_at')->limit(1)->get();
            if(count($userPeerConnection) > 0)
            {
                $this->answer = $userPeerConnection[0]->user_peer_connection;
                $this->dispatch('AcceptAnswer');
                //CurrentVideoSession::where('user_id', $this->user_id)->delete();
            }
        }

    }
    public function render()
    {
        return view('livewire.online-listener');
    }
}
