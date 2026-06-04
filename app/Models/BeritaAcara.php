<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    protected $table = 'berita_acaras';

    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_peserta',
        'hadir',
        'tidak_hadir',
        'catatan',
        'signature_guru'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function daftarHadirs()
    {
        return $this->hasMany(DaftarHadir::class, 'berita_acara_id');
    }
}
