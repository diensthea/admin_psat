<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['user_id', 'nip', 'nama'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beritaAcaras()
    {
        return $this->hasMany(BeritaAcara::class);
    }
}
