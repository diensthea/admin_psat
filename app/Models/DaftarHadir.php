<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarHadir extends Model
{
    protected $table = 'daftar_hadirs';

    protected $fillable = [
        'berita_acara_id',
        'siswa_id',
        'status',
        'signature_siswa'
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_acara_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
