<?php

namespace App\Livewire\Settings;

use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class Language extends Component
{
    public $language= 'en'; // Default language

    public function mount()
    {
        $this->language = session()->get('locale', $this->language);
    }

    public function updateLanguage()
    {
        session()->put('locale', $this->language);
        return redirect()->route('settings.language')->with('success', __('Language updated successfully.'));
    }

    public function render()
    {
        return view('livewire.settings.language');
    }
}
