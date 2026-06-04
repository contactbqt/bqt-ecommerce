<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class JoditTextEditor extends Component
{
    #[Modelable]
    public $value = '';

    public $label;
    public $placeholder = '';
    public $buttons = [
        'bold','italic','underline','strikethrough','ul','ol','link','image'
    ];

    public function render()
    {
        return view('livewire.jodit-text-editor');
    }
}
