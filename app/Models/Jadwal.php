<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> origin/agung
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
<<<<<<< HEAD
    //
=======
    use HasFactory;

    protected $fillable = [
    'konselor_id',
    'hari',
    'waktu',
    'status',
];

    public function Konselor()
    {
        return $this->belongsTo(Konselor::class);
    }
>>>>>>> origin/agung
}
