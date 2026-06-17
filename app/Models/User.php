<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasPermissions, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function intern()
    {
        return $this->hasOne(Intern::class);
    }

    public function isAdmin()
    {
        return $this->hasAnyRole([
            'super_admin',
            'admin_full',
            'admin_user_manager',
            'admin_data_manager',
        ]) || in_array($this->role, ['admin', 'super_admin', 'admin_full', 'admin_user_manager', 'admin_data_manager'], true);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin') || $this->role === 'super_admin';
    }

    public function isAdminFull()
    {
        return $this->hasRole('admin_full') || $this->role === 'admin_full';
    }

    public function isAdminUserManager()
    {
        return $this->hasRole('admin_user_manager') || $this->role === 'admin_user_manager';
    }

    public function isAdminDataManager()
    {
        return $this->hasRole('admin_data_manager') || $this->role === 'admin_data_manager';
    }

    public function isIntern()
    {
        return $this->hasRole('intern') || $this->role === 'intern';
    }

    public function isMentor()
    {
        return $this->hasRole('mentor') || $this->role === 'mentor';
    }

    public function isInstitusi()
    {
        return $this->hasRole('institusi') || $this->role === 'institusi';
    }

    public function isIndustri()
    {
        return $this->hasRole('industri') || $this->role === 'industri';
    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    public function institusi()
    {
        return $this->hasOne(Institusi::class);
    }

    public function industri()
    {
        return $this->hasOne(Industri::class);
    }
}
