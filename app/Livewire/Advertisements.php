<?php

namespace App\Livewire;

use App\Models\Advertisement;
use Livewire\Component;

class Advertisements extends Component
{
    public $advertisements;
    public function mount()
    {
        $this->advertisements = Advertisement::hasActive()->get();
    }
    public function render()
    {
        return view('livewire.advertisements');
    }
}
