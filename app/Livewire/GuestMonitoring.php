<?php

namespace App\Livewire;

use App\Models\CurrentVideoSession;
use Illuminate\Support\Carbon;
use Livewire\Component;

class GuestMonitoring extends Component
{
    public $currentSession;
    public $count;
    public $jobProcess;
    public $callerCount;
    public $offer;
    public $answer;
    public function mount()
    {
        $this->currentSession = null;
        $this->callerCount = 0;
        $this->jobProcess = false;
        $this->offer = null;
        $this->answer = null;
    }
    public function getSessionGuestCount()
    {
        if ($this->jobProcess === false) {
            $currentCount = CurrentVideoSession::where('user_id', auth()->user()->id)->where('created_at', '>', Carbon::now()->subSeconds(15)->getTimestamp())->orderBy('created_at','DESC')->count();
            if($currentCount > 0)
            {
                $this->getCurrentGuest();
                // $this->dispatch('calling_audio');
                $this->jobProcess = true;
            }
        }
    }
    public function createAnswerSession($answer)
    {
        CurrentVideoSession::where('user_id', Auth()->user()->id)->latest()->update(
            [
                'user_peer_connection' => $answer,
                'remarks' => 'Answer call'
            ]
        );
    }
    public function getCurrentGuest()
    {
        $this->currentSession = CurrentVideoSession::where('user_id', auth()->user()->id)->orderBy('created_at','DESC')->limit(1)->get();
        if ($this->currentSession)
        {
            $this->offer = $this->currentSession[0]->guest_peer_connection;
        }
    }

    public function render()
    {
        return view('livewire.guest-monitoring');
    }
}
