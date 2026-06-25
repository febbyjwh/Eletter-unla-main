<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'user_id',
        'unit_pengirim_id',
        'unit_penerima_id',

        'jenis_surat',
        'no_surat',
        'pengirim',
        'pembuat',
        'penerima',
        'pembuat',
        'tujuan',
        'penanda_tangan',
        'pengupload',
        'perihal',
        'tanggal',
        'file_surat',

        'created_by',
        'updated_by',
        'created_role_id',
        'updated_role_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE AUDIT
    |--------------------------------------------------------------------------
    */

    public function creatorRole()
    {
        return $this->belongsTo(Role::class, 'created_role_id');
    }

    public function updaterRole()
    {
        return $this->belongsTo(Role::class, 'updated_role_id');
    }

    /*
    |--------------------------------------------------------------------------
    | KHUSUS SURAT MASUK (dipakai kalau jenis_surat = 'masuk')
    |--------------------------------------------------------------------------
    */

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeMasuk($query)
    {
        return $query->where('jenis_surat', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis_surat', 'keluar');
    }

    public function unitPengirim()
    {
        return $this->belongsTo(Unit::class, 'unit_pengirim_id', 'unit_id');
    }

    public function unitPenerima()
    {
        return $this->belongsTo(Unit::class, 'unit_penerima_id', 'unit_id');
    }
}
