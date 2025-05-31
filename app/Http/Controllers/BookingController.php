<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Mahasiswa;

class BookingController extends Controller
{
    // 1. Melihat semua jadwal yang tersedia
    public function lihatJadwal()
    {
        $jadwals = Jadwal::whereDoesntHave('booking')->get(); // hanya jadwal yang belum dibooking
        return response()->json($jadwals);
    }

    // 2. Membuat booking baru
    public function bookingJadwal(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        // Cek apakah jadwal sudah dibooking
        if (Booking::where('jadwal_id', $request->jadwal_id)->exists()) {
            return response()->json(['message' => 'Jadwal sudah dibooking.'], 409);
        }

        $booking = Booking::create([
            'nim' => $request->nim,
            'tanggal' => now(),
            'status' => 'booked',
            'jadwal_id' => $request->jadwal_id,
        ]);

        return response()->json(['message' => 'Booking berhasil.', 'booking' => $booking]);
    }

    // 3. Reschedule booking
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'jadwal_id_baru' => 'required|exists:jadwals,id',
        ]);

        $booking = Booking::findOrFail($id);

        // Pastikan jadwal baru belum dibooking
        if (Booking::where('jadwal_id', $request->jadwal_id_baru)->exists()) {
            return response()->json(['message' => 'Jadwal baru sudah dibooking.'], 409);
        }

        $booking->jadwal_id = $request->jadwal_id_baru;
        $booking->tanggal = now();
        $booking->save();

        return response()->json(['message' => 'Reschedule berhasil.', 'booking' => $booking]);
    }

    // 4. Cancel booking
    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'cancelled';
        $booking->save();

        return response()->json(['message' => 'Booking dibatalkan.', 'booking' => $booking]);
    }
}
