<?php

namespace App\Http\Controllers\Tiket;

use App\Models\User;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// Import Notification Classes
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketRejectedNotification;
use App\Notifications\TicketCompletedNotification;

class TiketActionController extends Controller
{
    /**
     * Assign tiket ke teknisi
     */
    public function assign(Request $request, $id)
{
    DB::beginTransaction();
    
    try {
        $validated = $request->validate([
            'teknisi_id' => 'required|exists:users,id',
        ]);

        // Load relasi untuk email
        $tiket = Tiket::with(['user', 'departemen', 'urgency'])->findOrFail($id);
        
        // Validasi teknisi (kecuali administrator)
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

        // Get teknisi untuk message
        $teknisi = User::findOrFail($validated['teknisi_id']);

        // Update tiket
        $tiket->teknisi_id = $validated['teknisi_id'];
        $tiket->status = 'progress';
        $tiket->save();

        // Load relasi teknisi
        $tiket->load('teknisi');

        DB::commit();

        // Kirim email notifikasi
        if ($teknisi->email) {
            try {
                Notification::send([$teknisi], new TicketAssignedNotification($tiket));
            } catch (\Exception $e) {
                Log::error('Failed to send assign notification email: ' . $e->getMessage());
            }
        }

        // ✅ PENTING: Return dengan session success
        return redirect()->back()
            ->with('success', 'Tiket berhasil di-assign ke teknisi ' . $teknisi->name . ' ✅');

    } catch (\Exception $e) {
        DB::rollback();
        
        return redirect()->back()
            ->with('error', 'Gagal assign teknisi: ' . $e->getMessage());
    }
}


    /**
     * Unassign teknisi dari tiket
     */
    public function unassign($id)
    {
        DB::beginTransaction();
        
        try {
            $tiket = Tiket::findOrFail($id);
            
            $tiket->teknisi_id = null;
            $tiket->status = 'open';
            $tiket->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Teknisi berhasil di-unassign dari tiket');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal unassign teknisi: ' . $e->getMessage());
        }
    }

    /**
     * Update status tiket
     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'status' => 'required|in:open,progress,pending,finish,closed',
            ]);

            $tiket = Tiket::findOrFail($id);
            $tiket->status = $validated['status'];
            $tiket->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status tiket berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Teknisi accept tiket
     */
    public function accept($id)
    {
        DB::beginTransaction();
        
        try {
            $tiket = Tiket::findOrFail($id);
            
            // Validasi: hanya teknisi yang ditugaskan
            if (Auth::id() !== $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk accept tiket ini');
            }

            $tiket->status = 'progress';
            $tiket->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tiket berhasil diterima dan sedang dikerjakan');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal accept tiket: ' . $e->getMessage());
        }
    }

    /**
     * Teknisi reject tiket
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'catatan' => 'required|string|max:500',
            ]);

            // Load relasi untuk email
            $tiket = Tiket::with(['user', 'departemen', 'urgency', 'teknisi'])->findOrFail($id);
            
            // Validasi: hanya teknisi yang ditugaskan
            if (Auth::id() !== $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk reject tiket ini');
            }

            $tiket->status = 'pending';
            $tiket->catatan = 'DITOLAK: ' . $validated['catatan'];
            $tiket->teknisi_id = null;
            $tiket->save();

            DB::commit();

            // Kirim email ke admin
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $tiket->departemen_id)
                ->get();

            if ($admins->count() > 0) {
                Notification::send($admins, new TicketRejectedNotification($tiket, $validated['catatan']));
            }

            return redirect()->back()
                ->with('success', 'Tiket berhasil ditolak dan notifikasi telah dikirim ke Admin');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal reject tiket: ' . $e->getMessage());
        }
    }

    /**
     * Teknisi finish tiket
     */
    public function finish(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'solusi' => 'required|string',
            ]);

            // Load relasi untuk email
            $tiket = Tiket::with(['user', 'departemen', 'urgency', 'teknisi'])->findOrFail($id);
            
            // Validasi: hanya teknisi yang ditugaskan
            if (Auth::id() !== $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menyelesaikan tiket ini');
            }

            $tiket->status = 'finish';
            $tiket->solusi = $validated['solusi'];
            $tiket->tanggal_selesai = now();
            $tiket->save();

            DB::commit();

            // Kirim email ke admin
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $tiket->departemen_id)
                ->get();

            if ($admins->count() > 0) {
                Notification::send($admins, new TicketCompletedNotification($tiket));
            }

            return redirect()->back()
                ->with('success', 'Tiket berhasil diselesaikan dan notifikasi telah dikirim ke Admin');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan tiket: ' . $e->getMessage());
        }
    }
}
