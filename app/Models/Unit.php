<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Unit extends Authenticatable
{
    protected $table = 'unit';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'email',
        'google_drive_folder_id',
        'status',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token', // opsional tapi direkomendasikan
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
