<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Konselor extends Model
{
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
}
