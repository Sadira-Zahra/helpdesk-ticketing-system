<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\User;
use App\Models\Departemen;
use App\Models\Urgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TiketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Query tiket berdasarkan role dan departemen
        $query = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])
            ->where('status', '!=', 'close')
            ->orderBy('tanggal', 'desc');

        // FILTER DEPARTEMEN
        if ($role === 'user') {
            // User hanya lihat tiket miliknya
            $query->where('user_id', $user->id);
        } elseif ($role === 'admin') {
            // Admin hanya lihat tiket dari departemennya
            $query->where('departemen_id', $user->departemen_id);
        } elseif ($role === 'teknisi') {
            // Teknisi hanya lihat tiket yang ditugaskan ke dia
            $query->where('teknisi_id', $user->id);
        }
        // Administrator lihat semua

        $tikets = $query->paginate(10);

        // Data untuk dropdown
        $departemens = Departemen::all();
        $urgencies = Urgency::all();
        
        // Teknisi berdasarkan departemen
        if ($role === 'administrator') {
            $teknisis = User::where('role', 'teknisi')->get();
        } else {
            $teknisis = User::where('role', 'teknisi')
                            ->where('departemen_id', $user->departemen_id)
                            ->get();
        }

        return view('tiket.index', compact('tikets', 'departemens', 'urgencies', 'teknisis', 'role'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'keterangan' => 'required|string',
                'urgency_id' => 'required|exists:urgency,id',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $urgency = Urgency::find($validated['urgency_id']);
            $urgencyPrefix = strtoupper(substr($urgency->urgency, 0, 1));
            $date = Carbon::now()->format('Ymd');
            $count = Tiket::whereDate('tanggal', Carbon::today())->count() + 1;
            $nomor = sprintf('%s-%s-%03d', $urgencyPrefix, $date, $count);

            $deadline = Carbon::now()->addHours($urgency->jam);

            if ($request->hasFile('gambar')) {
                $validated['gambar'] = $request->file('gambar')->store('tiket', 'public');
            }

            Tiket::create([
                'user_id' => $user->id,
                'departemen_id' => $user->departemen_id,
                'nomor' => $nomor,
                'tanggal' => Carbon::now(),
                'judul' => $validated['judul'],
                'keterangan' => $validated['keterangan'],
                'urgency_id' => $validated['urgency_id'],
                'gambar' => $validated['gambar'] ?? null,
                'status' => 'open',
                'tanggal_selesai' => $deadline,
            ]);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil dibuat dengan nomor: ' . $nomor);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);
            $user = Auth::user();
            
            // PERBAIKAN: Teknisi berdasarkan departemen tiket
            if ($user->role === 'administrator') {
                $teknisis = User::where('role', 'teknisi')->get();
            } else {
                $teknisis = User::where('role', 'teknisi')
                                ->where('departemen_id', $tiket->departemen_id)
                                ->get();
            }
            
            return response()->json([
                'success' => true,
                'tiket' => $tiket,
                'teknisis' => $teknisis,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }
    }
}
