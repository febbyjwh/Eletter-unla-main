<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'unit_id',
        'role_id',
        // 'google_access_token',
        // 'google_refresh_token',
        // 'google_token_expires_at',
        // 'google_drive_folder_id',
        'status' // 0 = nonaktif, 1 = aktif
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
     * Relasi ke Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Cek apakah user punya role tertentu
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->role
            && $this->role->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    public function hasGoogleConnected()
    {
        return !empty($this->google_access_token);
    }

    public function getGoogleClientToken(): ?string
    {
        return $this->google_access_token;
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
