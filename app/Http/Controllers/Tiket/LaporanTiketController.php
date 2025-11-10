<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanTiketController extends Controller
{
     public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $startDate = $request->get('start_date', null);
        $endDate = $request->get('end_date', null);
        $departemenId = $request->get('departemen_id', null);

        // Jika filter belum dipilih, jangan tampilkan data
        $tikets = collect();
        $showData = false;

        if ($startDate && $endDate) {
            $showData = true;

            // Query laporan tiket
            $query = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])
                ->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->orderBy('tanggal', 'desc');

            // FILTER DEPARTEMEN BERDASARKAN ROLE
            if ($role === 'user') {
                $query->where('user_id', $user->id);
            } elseif ($role === 'admin') {
                $query->where('departemen_id', $user->departemen_id);
            } elseif ($role === 'teknisi') {
                $query->where('teknisi_id', $user->id);
            } elseif ($role === 'administrator' && $departemenId) {
                $query->where('departemen_id', $departemenId);
            }

            $tikets = $query->paginate(15);
        }

        // Data departemen untuk dropdown (hanya untuk administrator)
        $departemens = $role === 'administrator' ? Departemen::all() : collect();

        return view('tiket.laporan_tiket', compact(
            'tikets', 'startDate', 'endDate', 'departemens', 'departemenId', 'role', 'showData'
        ));
    }
}
