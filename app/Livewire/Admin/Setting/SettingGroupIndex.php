<?php

namespace App\Livewire\Admin\Setting;

use App\Models\SettingGroup;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class SettingGroupIndex extends Component
{
    public function render()
    {
        $groups = SettingGroup::withCount('settings')->orderBy('id', 'asc')->get();
        return view('livewire.admin.setting.setting-group-index', [
            'groups' => $groups
        ]);
    }
}
