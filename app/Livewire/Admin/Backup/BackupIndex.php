<?php

namespace App\Livewire\Admin\Backup;

use Livewire\Component;
use App\Models\DbBackupLog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class BackupIndex extends Component
{
    public $backups = [];

    public function mount()
    {
        if (!auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized access. Only Super Admins can manage backups.');
        }
        $this->loadBackups();
    }

    public function loadBackups()
    {
        $this->backups = DbBackupLog::orderBy('created_at', 'desc')->get();
    }

    public function createBackup()
    {

        Artisan::call('backup:run', ['--only-db' => true]);

        dd(Artisan::output());

        try {
            // Run the spatie backup command
            // --only-db ensures we only backup the database as requested
            Artisan::call('backup:run', ['--only-db' => true]);
            
            // Get the last created file in storage/app/backup/
            $files = Storage::disk('backup')->allFiles('/');
            if (!empty($files)) {
                $lastFile = end($files);
                $fileSize = $this->formatBytes(Storage::disk('backup')->size($lastFile));
                
                DbBackupLog::create([
                    'file_name' => basename($lastFile),
                    'file_size' => $fileSize,
                    'path' => $lastFile,
                    'created_by' => auth()->user()->id,
                    'created_at' => now(),
                    'status' => 'success',
                    'error_message' => '',
                ]);
            }

            session()->flash('success', 'Database backup created successfully.');
            $this->loadBackups();
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            DbBackupLog::create([
                'file_name' => 'N/A',
                'file_size' => '0 B',
                'path' => '',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_by' => auth()->user()->id,
                'created_at' => now(),
            ]);
            session()->flash('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function downloadBackup($id)
    {
        $backup = DbBackupLog::findOrFail($id);
        $filePath = $backup->path;

        if (Storage::disk('backup')->exists($filePath)) {
            return Storage::disk('backup')->download($filePath);
        }

        session()->flash('error', 'Backup file not found on disk.');
    }

    public function deleteBackup($id)
    {
        $backup = DbBackupLog::findOrFail($id);
        $filePath = $backup->path;

        if (Storage::disk('backup')->exists($filePath)) {
            Storage::disk('backup')->delete($filePath);
        }

        $backup->delete();
        $this->loadBackups();
        session()->flash('success', 'Backup deleted successfully.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function render()
    {
        return view('livewire.admin.backup.backup-index');
    }
}
