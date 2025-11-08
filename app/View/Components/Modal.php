<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $show = false;
    public $maxWidth = '2xl';

    /**
     * Create a new component instance.
     */
    public function __construct($maxWidth = '2xl')
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.modal');
    }

    /**
     * The maximum width of the modal.
     *
     * @return string
     */
    public function maxWidthClass()
    {
        return [
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
        ][$this->maxWidth];
    }
}
