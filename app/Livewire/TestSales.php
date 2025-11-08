<?php

namespace App\Livewire;

use Livewire\Component;

class TestSales extends Component
{
    public $showModal = false;
    public $message = 'Component loaded';

    public function openModal()
    {
        $this->showModal = true;
        $this->message = 'Modal opened!';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->message = 'Modal closed';
    }

    public function render()
    {
        return view('livewire.test-sales')->layout('layouts.app');
    }
}
