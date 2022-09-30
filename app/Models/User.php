<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Notifiable, LaratrustUserTrait;

    protected $fillable = ['name', 'email', 'password', 'type', 'image', 'code', 'extension', 'supervisor_id'];

    protected $appends = ['image_path'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }


    public function getImagePathAttribute()
    {
        if ($this->image) {
            return Storage::url('uploads/' . $this->image);
        }

        return asset('admin_assets/images/default.png');
    }



    public function scopeWhenRoleId($query, $roleId)
    {
        return $query->when($roleId, function ($q) use ($roleId) {

            return $q->whereHas('roles', function ($qu) use ($roleId) {

                return $qu->where('id', $roleId);
            });
        });
    }


    public function hasImage()
    {
        return $this->image != null;
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakModel::class, 'employee_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'supervisor_id', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id');
    }

    public function managerSupervisors()
    {
        return $this->hasMany(User::class, 'manager_id', 'id');
    }

    public function supervisorManager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }
}
