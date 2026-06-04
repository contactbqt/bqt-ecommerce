<?php

namespace App\Livewire\Admin\Dashboard;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;

#[Layout('components.layouts.admin-app')]
class DashboardIndex extends Component
{
    public function render()
    {
        $data = array();
        
        // Basic user statistics
        $data['totalUsers'] = User::count();
        $data['adminUsers'] = User::where('user_type', 'admin')->count();
        $data['regularUsers'] = User::where('user_type', '!=', 'admin')->count();
        $data['recentUsers'] = User::whereBetween('created_at', [
            now()->subDays(30),
            now()
        ])->count();

        return view('livewire.admin.dashboard.dashboard-index', $data);
    }
}
