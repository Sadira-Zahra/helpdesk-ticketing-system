<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tiket';

    protected $fillable = [
        'user_id',
        'departemen_id',
        'nomor',
        'tanggal',
        'judul',
        'keterangan',
        'urgency_id',
        'gambar',
        'status',
        'tanggal_selesai',
        'teknisi_id',
        'catatan',
        'solusi',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Relasi ke User (pembuat tiket)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Teknisi (yang handle tiket)
     */
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    /**
     * Relasi ke Departemen
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Relasi ke Urgency
     */
    public function urgency()
    {
        return $this->belongsTo(Urgency::class, 'urgency_id');
    }

    /**
     * Status color badge
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'warning',
            'pending' => 'info',
            'progress' => 'primary',
            'finish' => 'success',
            'close' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'open' => 'Open',
            'pending' => 'Pending',
            'progress' => 'Progress',
            'finish' => 'Finish',
            'close' => 'Close',
            default => $this->status,
        };
    }
}
