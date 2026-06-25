<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'no_surat',
        'pengirim',
        'pembuat',
        'tujuan',
        'penanda_tangan',
        'pengupload',
        'penerima',
        'perihal',
        'tanggal',
        'file_surat',
        'user_id',
        'created_by',
        'updated_by',
        'created_role_id',
        'updated_role_id',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke template
     */
    public function template()
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }

    /**
     * Relasi ke user pembuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user pengupdate
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke role pembuat
     */
    public function creatorRole()
    {
        return $this->belongsTo(Role::class, 'created_role_id');
    }

    /**
     * Relasi ke role pengupdate
     */
    public function updaterRole()
    {
        return $this->belongsTo(Role::class, 'updated_role_id');
    }
}
