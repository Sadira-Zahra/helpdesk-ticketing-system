<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TiketActionController extends Controller
{
    public function assign(Request $request, $id)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'administrator'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        try {
            $validated = $request->validate([
                'teknisi_id' => 'required|exists:users,id',
                'catatan' => 'nullable|string',
            ]);

            $tiket = Tiket::findOrFail($id);
            $teknisi = \App\Models\User::where('id', $validated['teknisi_id'])
                ->where('role', 'teknisi')
                ->first();

            if (!$teknisi) {
                return redirect()->back()->with('error', 'User yang dipilih bukan teknisi!');
            }

            // VALIDASI DEPARTEMEN
            if ($user->role === 'admin') {
                if ($teknisi->departemen_id !== $tiket->departemen_id) {
                    return redirect()->back()->with('error', 'Anda hanya bisa assign teknisi dari departemen yang sama!');
                }
            }

            $tiket->update([
                'teknisi_id' => $validated['teknisi_id'],
                'catatan' => $validated['catatan'],
                'status' => 'pending',
            ]);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil ditugaskan ke ' . $teknisi->nama);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'status' => 'required|in:open,pending,progress,finish,close',
                'solusi' => 'nullable|string',
            ]);

            $tiket = Tiket::findOrFail($id);

            if ($user->role === 'user' && $validated['status'] !== 'close') {
                return redirect()->back()->with('error', 'User hanya bisa close tiket!');
            }

            if ($user->role === 'teknisi') {
                if (!in_array($validated['status'], ['pending', 'progress', 'finish'])) {
                    return redirect()->back()->with('error', 'Teknisi tidak bisa mengubah status ke ' . $validated['status']);
                }
                if ($tiket->teknisi_id !== $user->id) {
                    return redirect()->back()->with('error', 'Anda tidak bisa mengubah tiket ini!');
                }
            }

            $updateData = ['status' => $validated['status']];

            if ($validated['status'] === 'finish') {
                $updateData['solusi'] = $validated['solusi'];
                $updateData['tanggal_selesai'] = Carbon::now();
            }

            $tiket->update($updateData);

            return redirect()->route('tiket.index')
                ->with('success', 'Status tiket berhasil diubah menjadi: ' . $validated['status']);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function decline($id)
    {
        $user = Auth::user();

        if ($user->role !== 'teknisi') {
            return redirect()->back()->with('error', 'Hanya teknisi yang bisa menolak tiket!');
        }

        try {
            $tiket = Tiket::findOrFail($id);

            if ($tiket->teknisi_id !== $user->id) {
                return redirect()->back()->with('error', 'Anda tidak bisa menolak tiket ini!');
            }

            if ($tiket->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya tiket pending yang bisa ditolak!');
            }

            $tiket->update([
                'teknisi_id' => null,
                'catatan' => null,
                'status' => 'open',
            ]);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil ditolak');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function unassign($id)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'administrator'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        try {
            $tiket = Tiket::findOrFail($id);

            $tiket->update([
                'teknisi_id' => null,
                'catatan' => null,
                'status' => 'open',
            ]);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil di-unassign');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Teknisi accept ticket - Status pending → progress
     */
    public function accept($id)
    {
        $user = Auth::user();

        if ($user->role !== 'teknisi') {
            return redirect()->back()->with('error', 'Hanya teknisi yang bisa menerima tiket!');
        }

        try {
            $tiket = Tiket::findOrFail($id);

            if ($tiket->teknisi_id !== $user->id) {
                return redirect()->back()->with('error', 'Tiket ini bukan untuk Anda!');
            }

            if ($tiket->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya tiket pending yang bisa diterima!');
            }

            $tiket->update(['status' => 'progress']);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket diterima, status berubah menjadi progress');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Teknisi reject ticket dengan alasan
     */
    public function reject(Request $request, $id)
{
    $user = Auth::user();

    if ($user->role !== 'teknisi') {
        return redirect()->back()->with('error', 'Hanya teknisi yang bisa menolak tiket!');
    }

    $validated = $request->validate([
        'catatan' => 'required|string',
    ], [
        'catatan.required' => 'Alasan penolakan harus diisi',
    ]);

    try {
        $tiket = Tiket::findOrFail($id);

        if ($tiket->teknisi_id !== $user->id) {
            return redirect()->back()->with('error', 'Tiket ini bukan untuk Anda!');
        }

        if ($tiket->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya tiket pending yang bisa ditolak!');
        }

        // Simpan alasan penolakan di kolom catatan, kembali ke status open
        // Teknisi di-null agar admin bisa assign ulang
        $tiket->update([
            'status' => 'open',
            'teknisi_id' => null,
            'catatan' => $validated['catatan'], // Alasan penolakan
        ]);

        return redirect()->route('tiket.index')
            ->with('success', 'Tiket ditolak. Admin akan melihat alasan penolakan Anda.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    /**
     * Teknisi finish ticket - Status progress → finish
     */
    public function finish(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'teknisi') {
            return redirect()->back()->with('error', 'Hanya teknisi yang bisa menyelesaikan tiket!');
        }

        $validated = $request->validate([
            'solusi' => 'nullable|string',
        ]);

        try {
            $tiket = Tiket::findOrFail($id);

            if ($tiket->teknisi_id !== $user->id) {
                return redirect()->back()->with('error', 'Tiket ini bukan untuk Anda!');
            }

            if ($tiket->status !== 'progress') {
                return redirect()->back()->with('error', 'Hanya tiket progress yang bisa diselesaikan!');
            }

            $tiket->update([
                'status' => 'finish',
                'solusi' => $validated['solusi'],
                'tanggal_selesai' => Carbon::now(),
            ]);

            return redirect()->route('tiket.index')
                ->with('success', 'Tiket berhasil diselesaikan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
