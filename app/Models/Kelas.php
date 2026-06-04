<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama_kelas'];

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'kelas_siswa', 'kelas_id', 'siswa_id')->withTimestamps();
    }

    public function beritaAcaras()
    {
        return $this->hasMany(BeritaAcara::class);
    }
}
