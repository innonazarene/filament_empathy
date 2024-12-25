<?php

namespace App\Livewire;

use App\Models\Patron;
use Livewire\Component;

class Patrons extends Component
{
    public $patrons;
    public function mount()
    {
        $this->patrons = Patron::get();
    }
    public function render()
    {
        return view('livewire.patrons');
    }
}
