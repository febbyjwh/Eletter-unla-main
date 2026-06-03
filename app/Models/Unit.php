<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'email',
        'google_drive_folder_id',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
