<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Mahasiswa extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'password',
        'tanggal',
    ];

    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function Booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
    


    
}
