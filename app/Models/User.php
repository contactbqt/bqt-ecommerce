<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug_name',
        'email',
        'password',
        'user_type',
        'provider_name',
        'provider_id',
        'provider_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function doctorDepartments()
    {
        return $this->hasMany(DoctorDepartment::class, 'doctor_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'doctor_departments', 'doctor_id', 'department_id');
    }

    public function appointmentTimeslot()
    {
        return $this->hasMany(AppointmentTimeslot::class, 'doctor_id');
    }

    // Custom relation for grouped schedules
    public function schedules()
    {
        return $this->hasMany(AppointmentTimeslot::class, 'doctor_id')
            ->selectRaw('doctor_id, day_of_week, GROUP_CONCAT(CONCAT( DATE_FORMAT(start_time, "%h:%i %p"), " - ", DATE_FORMAT(end_time, "%h:%i %p")) 
            ORDER BY start_time ASC SEPARATOR ", ") as timeslots')
            ->groupBy('doctor_id', 'day_of_week')
            ->orderBy('day_of_week', 'ASC');
    }

    public function appointmentBookingSchedule()
    {
        return $this->hasMany(AppointmentBookingSchedule::class, 'patient_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Check if user has purchased the product
     */
    public function hasPurchased($productId)
    {
        return \App\Models\OrderDetail::whereHas('order', function($q) {
            $q->where('user_id', $this->id);
        })->where('product_id', $productId)->exists();
    }

    public function getDoctorAppointments()
    {
        return $this->hasMany(AppointmentBookingSchedule::class, 'doctor_id');
    }
    

}
