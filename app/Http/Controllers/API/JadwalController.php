<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    // Tampilkan semua jadwal milik konselor yang sedang login
    public function index()
    {
        $jadwal = Jadwal::where('konselor_id', Auth::id())->get();
        return response()->json($jadwal);
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|date',
            'waktu' => 'required|date_format:H:i|before_or_equal:21:00',
            'status' => 'required|string'
        ]);

        $jadwal = Jadwal::create([
            'konselor_id' => Auth::id(),
            'hari' => $request->hari,
            'waktu' => $request->waktu,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil dibuat.',
            'data' => $jadwal
        ], 201);
    }

    // Tampilkan 1 jadwal
    public function show($id)
    {
        $jadwal = Jadwal::where('konselor_id', Auth::id())->findOrFail($id);
        return response()->json($jadwal);
    }

    // Update jadwal
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::where('konselor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'hari' => 'sometimes|date',
            'waktu' => 'sometimes|date_format:H:i|before_or_equal:21:00',
            'status' => 'sometimes|string'
        ]);

        $jadwal->update($request->only(['hari', 'waktu', 'status']));

        return response()->json([
            'message' => 'Jadwal berhasil diupdate.',
            'data' => $jadwal
        ]);
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $jadwal = Jadwal::where('konselor_id', Auth::id())->findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus.'
        ]);
    }
}
