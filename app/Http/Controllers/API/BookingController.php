<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Jadwal; // Pastikan ini di-import
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Konselor;

class BookingController extends Controller
{
    public function home(Request $request)
    {
        $user = $request->user()->load('mahasiswa');
        if (!$user) {
            return redirect()->route('login');
        }

        $today = now()->toDateString();
        // Ambil semua appointments (termasuk yang cancelled) untuk ditampilkan di home
        // Jika Anda hanya ingin yang aktif, tambahkan ->where('status', '!=', 'cancelled')
        $appointments = Booking::where('user_id', $user->id)
                                ->whereDate('tanggal', $today) // Mungkin Anda ingin melihat semua booking, tidak hanya hari ini
                                ->with('jadwal.konselor') // Eager load konselor juga untuk ditampilkan
                                ->get();

        return view('mahasiswa/home-mahasiswa', compact('user', 'appointments'));
    }

    public function lihatJadwal(Request $request)
    {
        // Mengambil jadwal yang statusnya 'available'
        $jadwals = Jadwal::where('status', 'available')->with('konselor')->get();

        if ($request->expectsJson()) {
            return response()->json($jadwals, 200);
        }

        return view('mahasiswa/jadwal-mahasiswa', compact('jadwals'));
    }

    public function showBookingForm()
    {
        // Mengambil jadwal yang statusnya 'available'
        $jadwalsTersedia = Jadwal::where('status', 'available')->with('konselor')->get();
        $user = Auth::user()->load('mahasiswa');
        $mahasiswa = $user->mahasiswa;

        return view('mahasiswa/booking-mahasiswa', compact('jadwalsTersedia', 'user', 'mahasiswa'));
    }

    public function bookingJadwal(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'jadwal_id' => 'required|exists:jadwal,id',
        ]);

        // Cek apakah jadwal sudah dibooking atau tidak available
        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        if ($jadwal->status !== 'available') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Jadwal ini tidak tersedia untuk dibooking.'], 409);
            }
            return back()->with('error', 'Jadwal ini tidak tersedia untuk dibooking.');
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'nim' => $request->nim,
            'tanggal' => now(), // Atau ambil tanggal dari jadwal jika jadwal memiliki tanggal
            'status' => 'booked',
            'jadwal_id' => $request->jadwal_id,
        ]);

        // Ubah status jadwal menjadi 'booked' setelah berhasil dibooking
        $jadwal->status = 'booked';
        $jadwal->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Booking berhasil dibuat!', 'booking' => $booking], 201);
        }

        return redirect()->route('home')->with('success', 'Booking berhasil dibuat!');
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'jadwal_id_baru' => 'required|exists:jadwal,id',
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Dapatkan jadwal lama dan jadwal baru
        $jadwalLama = Jadwal::findOrFail($booking->jadwal_id);
        $jadwalBaru = Jadwal::findOrFail($request->jadwal_id_baru);

        // Pastikan jadwal baru tersedia
        if ($jadwalBaru->status !== 'available') {
            return response()->json(['message' => 'Jadwal baru tidak tersedia.'], 409);
        }

        // Ubah status jadwal lama menjadi 'available'
        $jadwalLama->status = 'available';
        $jadwalLama->save();

        // Update booking dengan jadwal baru
        $booking->jadwal_id = $request->jadwal_id_baru;
        $booking->tanggal = now(); // Ini akan mengupdate tanggal booking ke hari ini. Mungkin Anda ingin mempertahankan tanggal jadwal baru.
        $booking->save();

        // Ubah status jadwal baru menjadi 'booked'
        $jadwalBaru->status = 'booked';
        $jadwalBaru->save();

        return response()->json(['message' => 'Reschedule berhasil.', 'booking' => $booking], 200);
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Dapatkan jadwal yang terkait dengan booking ini
        $jadwal = Jadwal::findOrFail($booking->jadwal_id);

        // Ubah status booking menjadi 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        // Ubah status jadwal menjadi 'available'
        $jadwal->status = 'available';
        $jadwal->save();

        return response()->json(['message' => 'Booking dibatalkan.', 'booking' => $booking], 200);
    }

    // Metode Admin tetap di sini sesuai permintaan, tidak diubah
    // Pastikan metode admin juga memperhitungkan status jadwal saat update/delete
    public function updateAppointmentAdmin(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validatedData = $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'status' => 'required|string|in:booked,cancelled,completed',
        ]);

        // Jika status diubah menjadi 'cancelled' atau 'completed'
        if ($booking->status === 'booked' && ($validatedData['status'] === 'cancelled' || $validatedData['status'] === 'completed')) {
            $jadwalLama = Jadwal::findOrFail($booking->jadwal_id);
            $jadwalLama->status = 'available'; // Mengembalikan jadwal lama ke available
            $jadwalLama->save();
        }

        $booking->update($validatedData);

        // Jika status diubah menjadi 'booked' untuk jadwal baru (jika ada perubahan jadwal_id)
        if ($validatedData['status'] === 'booked' && $booking->jadwal_id !== $validatedData['jadwal_id']) {
            $jadwalBaru = Jadwal::findOrFail($validatedData['jadwal_id']);
            $jadwalBaru->status = 'booked';
            $jadwalBaru->save();
        }


        if ($request->expectsJson()) {
            return response()->json(['message' => 'Appointment berhasil diperbarui!', 'booking' => $booking], 200);
        }
        return redirect()->route('appointments.index')->with('success', 'Appointment berhasil diperbarui!');
    }

    public function deleteAppointmentAdmin($id)
    {
        $booking = Booking::findOrFail($id);

        // Sebelum menghapus booking, kembalikan status jadwal menjadi 'available'
        $jadwal = Jadwal::findOrFail($booking->jadwal_id);
        $jadwal->status = 'available';
        $jadwal->save();

        $booking->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Booking berhasil dihapus.'], 200);
        }
        return redirect()->route('appointments.index')->with('success', 'Booking berhasil dihapus.');
    }
}