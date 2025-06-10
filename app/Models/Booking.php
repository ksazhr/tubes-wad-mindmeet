<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'tanggal',
        'status',
        'jadwal_id'
    ];

    // Relasi : Satu Mahasiswa bisa melakukan berkali-kali booking
    public function Mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    // Relasi : Satu jadwal hanya bisa booking satu kali
    public function Jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
