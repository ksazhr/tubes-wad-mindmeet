<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Casts\Attribute;
>>>>>>> kania
use Illuminate\Database\Eloquent\Model;

class Konselor extends Model
{
<<<<<<< HEAD
    //
=======
    use HasFactory;

    protected $fillable = [
        'nama',
        'spesialisasi',
        'email',
        'password',
    ];

    use HasFactory;

    // Relasi: Satu Konselor bisa dimiliki oleh banyak data Jadwal
    public function Jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Relasi: Satu Konselor bisa dimiliki oleh banyak data Feedback
    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }
>>>>>>> kania
}
