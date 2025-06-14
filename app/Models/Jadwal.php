<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
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
}
