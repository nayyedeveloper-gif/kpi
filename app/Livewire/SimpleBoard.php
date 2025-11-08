<?php

namespace App\Livewire;

use Livewire\Component;

class SimpleBoard extends Component
{
    public function render()
    {
        return view('livewire.simple-board')->layout('layouts.app');
    }
}
