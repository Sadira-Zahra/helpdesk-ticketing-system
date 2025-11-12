<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCompletedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // ✅ Arahkan ke login petugas
        $url = route('login_petugas');
        
        return (new MailMessage)
                    ->subject('Tiket Selesai Dikerjakan - #' . $this->ticket->nomor)
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Tiket telah diselesaikan oleh teknisi ' . ($this->ticket->teknisi ? $this->ticket->teknisi->name : 'Teknisi'))
                    ->line('Nomor Tiket: ' . $this->ticket->nomor)
                    ->line('Judul: ' . $this->ticket->judul)
                    ->line('Departemen: ' . $this->ticket->departemen->nama_departemen)
                    ->line('Solusi: ' . \Illuminate\Support\Str::limit($this->ticket->solusi, 100))
                    ->action('Login untuk Verifikasi', $url)
                    ->line('Terima kasih!');
    }
}
