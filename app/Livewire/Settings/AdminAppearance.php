<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class AdminAppearance extends Component
{
    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
