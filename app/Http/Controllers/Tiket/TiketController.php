<?php

namespace App\Http\Controllers\Tiket;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Urgency;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Import semua Notification classes
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketRejectedNotification;
use App\Notifications\TicketReopenedNotification;
use App\Notifications\TicketCompletedNotification;

class TiketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Query tiket berdasarkan role dan departemen
        $query = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])
            ->where('status', '!=', 'closed')
            ->orderBy('tanggal', 'desc');

        // FILTER DEPARTEMEN
        if ($role === 'user') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'admin') {
            $query->where('departemen_id', $user->departemen_id);
        } elseif ($role === 'teknisi') {
            $query->where('teknisi_id', $user->id);
        }

        $tikets = $query->paginate(10);

        // Data untuk dropdown
        $departemens = Departemen::all();
        $urgencies = Urgency::all();
        
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

        DB::beginTransaction();
        
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

            $tiket = Tiket::create([
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

            DB::commit();

            // Kirim notifikasi ke Admin dan Administrator
            $recipients = User::whereIn('role', ['admin', 'administrator'])
                ->when($user->role === 'user', function($query) use ($user) {
                    return $query->where('departemen_id', $user->departemen_id);
                })
                ->get();

            Notification::send($recipients, new TicketCreatedNotification($tiket));

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil dibuat dengan nomor: ' . $nomor);

        } catch (\Exception $e) {
            DB::rollback();
            
            if (isset($validated['gambar'])) {
                Storage::disk('public')->delete($validated['gambar']);
            }
            
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

    public function assign(Request $request, $id)
{
    DB::beginTransaction();
    
    try {
        $validated = $request->validate([
            'teknisi_id' => 'required|exists:users,id',
        ]);

        // Load relasi yang dibutuhkan
        $tiket = Tiket::with(['user', 'departemen', 'urgency'])->findOrFail($id);
        
        $user = Auth::user();
        if ($user->role !== 'administrator') {
            $teknisi = User::where('id', $validated['teknisi_id'])
                ->where('departemen_id', $tiket->departemen_id)
                ->where('role', 'teknisi')
                ->first();
                
            if (!$teknisi) {
                throw new \Exception('Teknisi tidak ditemukan atau tidak sesuai departemen');
            }
        }

        // Get teknisi name untuk message
        $teknisi = User::findOrFail($validated['teknisi_id']);

        $tiket->teknisi_id = $validated['teknisi_id'];
        $tiket->status = 'progress';
        $tiket->save();

        // Load relasi teknisi setelah save
        $tiket->load('teknisi');

        DB::commit();

        // Kirim email ke teknisi
        if ($teknisi->email) {
            try {
                Notification::send([$teknisi], new TicketAssignedNotification($tiket));
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        // ✅ PENTING: Return dengan session success
        return redirect()->back()
            ->with('success', 'Tiket berhasil di-assign ke teknisi ' . $teknisi->name . ' dan notifikasi email telah dikirim');

    } catch (\Exception $e) {
        DB::rollback();
        
        return redirect()->back()
            ->with('error', 'Gagal assign teknisi: ' . $e->getMessage());
    }
}


    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'catatan' => 'required|string|max:500', // Pakai 'catatan' bukan 'alasan_penolakan'
            ]);

            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);
            
            if (Auth::id() !== $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menolak tiket ini');
            }

            $tiket->status = 'pending'; // Sesuai ENUM: open, pending, progress, finish, closed
            $tiket->catatan = 'DITOLAK: ' . $validated['catatan']; // Pakai kolom 'catatan'
            $tiket->teknisi_id = null;
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $tiket->departemen_id)
                ->get();

            Notification::send($admins, new TicketRejectedNotification($tiket, $validated['catatan']));

            return redirect()->back()
                ->with('success', 'Tiket berhasil ditolak dan notifikasi telah dikirim ke Admin');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function complete(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'solusi' => 'required|string',
            ]);

            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);
            
            if (Auth::id() !== $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menyelesaikan tiket ini');
            }

            $tiket->status = 'finish'; // Sesuai ENUM: open, pending, progress, finish, closed
            $tiket->solusi = $validated['solusi'];
            $tiket->tanggal_selesai = Carbon::now(); // Update tanggal selesai
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $tiket->departemen_id)
                ->get();

            Notification::send($admins, new TicketCompletedNotification($tiket));

            return redirect()->back()
                ->with('success', 'Tiket berhasil diselesaikan dan notifikasi telah dikirim ke Admin');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reopen($id)
    {
        DB::beginTransaction();
        
        try {
            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);
            
            if (Auth::user()->role !== 'administrator') {
                throw new \Exception('Hanya Administrator yang dapat membuka kembali tiket');
            }

            $tiket->status = 'open';
            $tiket->teknisi_id = null;
            $tiket->solusi = null;
            $tiket->catatan = null;
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $tiket->departemen_id)
                ->get();

            Notification::send($admins, new TicketReopenedNotification($tiket));

            return redirect()->back()
                ->with('success', 'Tiket berhasil dibuka kembali dan notifikasi telah dikirim ke Admin');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function close($id)
    {
        DB::beginTransaction();
        
        try {
            $tiket = Tiket::findOrFail($id);
            
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'administrator'])) {
                throw new \Exception('Anda tidak memiliki akses untuk menutup tiket');
            }

            if ($tiket->status !== 'finish') {
                throw new \Exception('Tiket harus diselesaikan terlebih dahulu sebelum ditutup');
            }

            $tiket->status = 'closed'; // Sesuai ENUM: open, pending, progress, finish, closed
            $tiket->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tiket berhasil ditutup');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
