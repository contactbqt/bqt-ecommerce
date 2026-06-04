<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use Hash;

#[Layout('components.layouts.doctor-auth')]
class DoctorLogin extends Component
{
    public string $email = '';
    public string $password = '';

    public function mount()
    {
        if (Auth::check() && Auth::user()->user_type === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
    }


    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
        //     throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        // }

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        if ($user->user_type !== 'doctor') {
            throw ValidationException::withMessages(['email' => 'You are not allowed to login here.']);
        }

        Auth::guard('doctor')->login($user);

        session()->regenerate();

        return redirect()->route('doctor.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.doctor-login');
    }
}

