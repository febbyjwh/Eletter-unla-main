<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
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

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
