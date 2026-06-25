<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';

    protected $fillable = [
        'no_surat',
        'pengirim',
        'penerima',
        'penanda_tangan',
        'tujuan',
        'pengupload',
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
     * Relasi: Satu surat bisa punya banyak disposisi
     */
    public function disposisis()
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    /**
     * Relasi: Surat dibuat oleh user tertentu
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Audit trail: siapa yang buat surat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Audit trail: siapa yang terakhir update surat
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Audit trail: role saat buat surat
     */
    public function creatorRole()
    {
        return $this->belongsTo(Role::class, 'created_role_id');
    }

    /**
     * Audit trail: role saat update surat
     */
    public function updaterRole()
    {
        return $this->belongsTo(Role::class, 'updated_role_id');
    }
}
