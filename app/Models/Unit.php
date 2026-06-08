<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Unit extends Authenticatable
{
    protected $table = 'unit';
    protected $primaryKey = 'unit_id';

    // App/Models/Unit.php
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'email',
        'status',
        'google_access_token',   
        'google_refresh_token',  
        'google_token_expires_at',
        'google_drive_folder_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function user()
    {
        return $this->hasOne(
            User::class,
            'unit_id',
            'unit_id'
        );
    }

    public function hasPermission($permission)
    {
        return true;
    }
}
