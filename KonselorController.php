<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Konselor;
<<<<<<< Updated upstream
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
=======
use App\Models\Booking;
use App\Models\User; // <--- PASTIKAN INI ADA UNTUK MENGAKSES MODEL USER
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
>>>>>>> Stashed changes

class KonselorController extends Controller
{
    public function __construct()
    {
<<<<<<< Updated upstream
        // Hanya user terautentikasi yang bisa mengakses semua method kecuali index dan show
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tambahkan fitur pencarian dan paginasi sederhana
        $konselor = Konselor::query();
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $konselor->where('name', 'like', "%{$searchTerm}%")
                     ->orWhere('email', 'like', "%{$searchTerm}%");
        }
        $konselor = $konselor->paginate(10); // 10 konselor per halaman
        return response()->json($konselor);
    }

    /**
=======
        $user = Auth::user()->load('konselor');

        if (!$user || $user->role !== 'konselor' || !$user->konselor) {
            abort(403, 'Anda tidak diizinkan mengakses dashboard konselor.');
        }

        $konselor = $user->konselor;
        $today = Carbon::today()->toDateString(); // Format YYYY-MM-DD

        // $todaysBookings: Ambil booking yang TANGGAL JADWALNYA adalah hari ini
        $todaysBookings = Booking::whereHas('jadwal', function ($query) use ($konselor, $today) {
                                // Filter berdasarkan konselor_id DAN tanggal jadwal
                                $query->where('konselor_id', $konselor->id)
                                      ->whereDate('hari', $today); // <-- FIX: Gunakan kolom 'hari' dari tabel 'jadwal'
                            })
                            ->with(['jadwal.konselor', 'mahasiswa'])
                            ->get();

        // $allKonselorBookings: Ambil semua booking untuk konselor ini (data kalender)
        $allKonselorBookings = Booking::whereHas('jadwal', function ($query) use ($konselor) {
                                $query->where('konselor_id', $konselor->id);
                            })
                            ->with(['jadwal', 'mahasiswa']) // Pastikan relasi 'jadwal' dan 'mahasiswa' dimuat
                            ->get()
                            ->map(function($booking) {
                                return [
                                    // <-- KRUSIAL FIX: Gunakan kolom 'hari' dari tabel 'jadwal' untuk tanggal
                                    'date' => Carbon::parse($booking->jadwal->hari)->toDateString(),
                                    'time' => Carbon::parse($booking->jadwal->waktu)->format('H:i'),
                                    'mahasiswa_name' => $booking->mahasiswa->nama ?? 'N/A',
                                    'booking_id' => $booking->id
                                ];
                            });

        $currentMonth = Carbon::now()->format('F Y');
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $daysInMonth = $firstDayOfMonth->daysInMonth;

        return view('konselor/home-konselor', compact('user', 'konselor', 'todaysBookings', 'allKonselorBookings', 'currentMonth', 'firstDayOfMonth', 'daysInMonth'));
    }


    /**
     * Tampilkan daftar konselor (untuk admin).
     * Metode ini dipanggil oleh rute 'manage.counselor'.
     */
    public function index()
    {
        // Ambil semua data konselor dari database
        $counselors = Konselor::all();
        
        // Kirim data ke view admin untuk ditampilkan
        return view('admin.kelola-konselor', compact('counselors'));
    }

    /**
     * Menampilkan form untuk membuat konselor baru.
     */
    public function create()
    {
        if (!Gate::allows('create-konselor', Auth::user())) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }
        return view('admin.konselor.create');
    }

    /**
>>>>>>> Stashed changes
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
<<<<<<< Updated upstream
        // Otorisasi: Hanya admin yang boleh menambah konselor
        if (!Gate::allows('manage-konselor', $request->user())) {
             return response()->json(['message' => 'Anda tidak diizinkan melakukan aksi ini.'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:konselor,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $konselor = Konselor::create($validatedData);
        return response()->json(['message' => 'Konselor berhasil ditambahkan.', 'data' => $konselor], 201);
=======
        if (!Gate::allows('manage-konselor', Auth::user())) {
            return redirect()->json(['message' => 'Anda tidak diizinkan melakukan aksi ini.'], 403);
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:konselor,email',
            'password' => 'required|string|min:8|confirmed',
            'user_id' => [
                'nullable', 'integer',
                Rule::unique('konselor', 'user_id'),
                Rule::exists('users', 'id')
            ],
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);

        $konselor = Konselor::create($validatedData);

        return redirect()->route('manage.counselor')->with('success', 'Konselor berhasil ditambahkan.');
>>>>>>> Stashed changes
    }

    /**
     * Display the specified resource.
     */
    public function show(Konselor $konselor)
    {
        return response()->json($konselor);
    }

    /**
<<<<<<< Updated upstream
=======
     * Menampilkan form untuk mengedit konselor.
     */
    public function edit(Konselor $konselor)
    {
        // Eager load relasi 'user' saat mengambil konselor untuk form edit
        $konselor = $konselor->load('user'); 

        if (!Gate::allows('update-konselor', $konselor)) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }
        return view('admin.konselor.edit', compact('konselor'));
    }


    /**
>>>>>>> Stashed changes
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
<<<<<<< Updated upstream
        // Otorisasi: Hanya admin atau konselor yang bersangkutan yang boleh melihat detail
        if (!Gate::allows('view-konselor', $request->user())) {
            return response()->json(['message' => 'Anda tidak diizinkan melakukan aksi ini.'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'spesialisasi' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:konselor,email,' . $konselor->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        $konselor->update($validatedData);
        return response()->json(['message' => 'Konselor berhasil diperbarui.', 'data' => $konselor]);
=======
        // Muat relasi 'user' karena kita akan memperbarui data user juga
        $konselor = $konselor->load('user');

        if (!Gate::allows('update-konselor', $konselor)) {
            return redirect()->back()->withErrors('Anda tidak diizinkan melakukan aksi ini.')->withInput();
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255', // Nama konselor (dari form)
            'spesialisasi' => 'required|string|max:255',
            'email' => [ // Email konselor (dari form)
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan email unik di tabel konselor, abaikan email konselor saat ini
                Rule::unique('konselor', 'email')->ignore($konselor->id),
            ],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional
        ]);

        // --- UPDATE DATA DI TABEL 'konselor' ---
        $konselor->nama = $validatedData['nama'];
        $konselor->spesialisasi = $validatedData['spesialisasi'];
        $konselor->email = $validatedData['email']; // Update email di tabel konselor
        // Password tidak perlu diupdate di sini karena model konselor tidak mengelola password lagi secara langsung
        $konselor->save(); // Simpan perubahan pada Konselor

        // --- UPDATE DATA DI TABEL 'users' YANG TERKAIT ---
        $user = $konselor->user; // Dapatkan objek User terkait
        if ($user) {
            $user->name = $validatedData['nama']; // <-- FIX: Perbarui nama di tabel users
            $user->email = $validatedData['email']; // <-- FIX: Perbarui email di tabel users
            
            // Perbarui password hanya jika diisi di form
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save(); // Simpan perubahan pada User
        }

        return redirect()->route('manage.counselor')->with('success', 'Data konselor berhasil diperbarui!');
>>>>>>> Stashed changes
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
<<<<<<< Updated upstream
        // Otorisasi: Hanya admin yang boleh menghapus konselor
        if (!Gate::allows('manage-konselor', $request->user())) {
            return response()->json(['message' => 'Anda tidak diizinkan melakukan aksi ini.'], 403);
        }

        $konselor = Konselor::findOrFail($id);
        $konselor->delete();
        return response()->json(['message' => 'Konselor berhasil dihapus.']);
=======
        // Pastikan Anda mengimpor kelas User di bagian atas file: use App\Models\User;
        
        if (!Gate::allows('manage-konselor', Auth::user())) {
            return redirect()->back()->withErrors('Anda tidak diizinkan melakukan aksi ini.')->withInput();
        }

        // --- Perubahan di sini: Simpan user_id sebelum menghapus konselor ---
        $userId = $konselor->user_id;

        // Hapus konselor (ini akan menghapus dari tabel 'konselor')
        $konselor->delete();

        // --- Perubahan di sini: Hapus juga user yang terkait ---
        if ($userId) { // Pastikan ada user_id yang valid
            $user = User::find($userId); // Cari user berdasarkan user_id yang terkait
            if ($user) {
                $user->delete(); // Hapus user dari tabel 'users'
            }
        }
        // --- Akhir perubahan ---
        
        return redirect()->route('manage.counselor')->with('success', 'Konselor dan User terkait berhasil dihapus.');
>>>>>>> Stashed changes
    }

}
