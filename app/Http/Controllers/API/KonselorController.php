<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Konselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KonselorController extends Controller
{
    public function __construct()
    {
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Konselor $konselor)
    {
        return response()->json($konselor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Otorisasi: Hanya admin yang boleh menghapus konselor
        if (!Gate::allows('manage-konselor', $request->user())) {
            return response()->json(['message' => 'Anda tidak diizinkan melakukan aksi ini.'], 403);
        }

        $konselor = Konselor::findOrFail($id);
        $konselor->delete();
        return response()->json(['message' => 'Konselor berhasil dihapus.']);
    }

}
