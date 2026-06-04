<?php

namespace App\Livewire\Admin\System;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class SystemReset extends Component
{
    public array $selectedModules = [];
    public string $confirmation = '';
    public array $modules = [];

    protected $rules = [
        'selectedModules' => 'required|array|min:1',
        'confirmation' => 'required',
    ];

    protected $messages = [
        'selectedModules.required' => 'Please select at least one module to reset.',
        'selectedModules.min' => 'Please select at least one module to reset.',
        'confirmation.required' => 'Confirmation text is required.',
    ];

    public function mount()
    {
        if (!auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized access. Only Super Admins can manage system reset.');
        }
        $this->modules = config('reset.modules', []);
    }

    public function resetModules()
    {
        $this->validate();

        if ($this->confirmation !== 'RESET DATA') {
            $this->addError('confirmation', 'You must type "RESET DATA" exactly to confirm.');
            return;
        }

        Log::info('System reset triggered', [
            'admin_id' => auth()->id(),
            'modules' => $this->selectedModules,
        ]);

        $modules = config('reset.modules');

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($this->selectedModules as $module) {
                if (!isset($modules[$module])) {
                    continue;
                }

                foreach ($modules[$module] as $table) {
                    if ($table === 'users') {
                        // Delete all users except the one with ID 1
                        DB::table('users')->where('id', '>', 1)->delete();
                    } else {
                        // Use truncate for other tables
                        // Note: TRUNCATE triggers an implicit commit in MySQL
                        DB::table($table)->truncate();
                    }
                }
            }

            $this->selectedModules = [];
            $this->confirmation = '';

            session()->flash('success', 'Selected modules reset successfully.');
        } catch (\Exception $e) {
            Log::error('System reset failed: ' . $e->getMessage());
            session()->flash('error', 'Reset failed: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function render()
    {
        return view('livewire.admin.system.system-reset')->layout('admin.layouts.app');
    }
}
