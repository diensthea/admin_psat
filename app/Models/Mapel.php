<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = ['kode_mapel', 'nama_mapel'];

    public function beritaAcaras()
    {
        return $this->hasMany(BeritaAcara::class);
    }
}
