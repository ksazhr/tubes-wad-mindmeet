<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    //
    protected $fillable = [
        'komentar',
        'rating',
        'nim',
        'konselor_id',
        'booking_id',
    ];

    // Relasi: Satu Mahasiswa bisa membuat banyak Feedback
    public function mahasiswa() // Nama fungsi harus diawali huruf kecil sesuai konvensi
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi: Setiap Feedback diberikan untuk satu Konselor
    // Gunakan belongsTo karena 'konselor_id' ada di tabel feedbacks
    public function konselor() // Nama fungsi harus diawali huruf kecil sesuai konvensi
    {
        return $this->belongsTo(Konselor::class);
    }

    // Relasi BARU yang hilang dan menyebabkan error "Undefined relationship [Booking]"
    // Setiap Feedback terkait dengan satu Booking
    // Gunakan belongsTo karena 'booking_id' ada di tabel feedbacks
    public function booking() // Nama fungsi harus diawali huruf kecil sesuai konvensi
    {
        return $this->belongsTo(Booking::class);
    }
}

