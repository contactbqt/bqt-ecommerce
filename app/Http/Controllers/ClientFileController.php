<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class ClientFileController extends Controller
{ 
    protected $root = 'clientFiles/reports';
    protected $disk = 'client';

    protected function fullPath($path = '')
    {
        $path = trim($path, '/');
        return $this->root . ($path ? '/' . $path : '');
    }

    public function index(Request $request, $path = '')
    {
        try {
            
            $key_arr = array_keys($request->query());
            $path = $key_arr[0];

            if (!Storage::disk($this->disk)->exists($path)) {
                abort(404, 'The requested folder does not exist.');
            }
           
            $fullPath = $this->fullPath($path); 
            $directories = Storage::disk($this->disk)->directories($path);

            /* Get all files in the current directory and subdirectories */
            $allowedExtensions = ['pdf'];

            $allFiles = Storage::disk($this->disk)->files($path);
            $allowFiles = collect($allFiles)->filter(function ($file) use ($allowedExtensions) {
                return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $allowedExtensions);
            })->values()->all();

            // Normalize directories for view
            $normalizedDirectories = [];
            foreach ($directories as $dir) {
                $relativePath = str_replace($this->root . '/', '', $dir);
                $normalizedDirectories[] = $relativePath;
            }

            $encryptedPath = Crypt::encryptString($path);
            return view('client-files.index', [
                'currentPath' => $encryptedPath,
                'directories' => $normalizedDirectories,
                'files' => $allowFiles,
            ]);

        } catch (\Exception $e) { 
            abort(403, 'Invalid or expired link');
        }
    }
    

    public function download(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path) {
            abort(404);
        }

        // Always sanitize path
        $path = ltrim($path, '/');

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($this->disk)->download($path);
    }


}
