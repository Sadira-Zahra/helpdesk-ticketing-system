<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketReopenedNotification extends Notification
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
                    ->subject('Tiket Dibuka Kembali - #' . $this->ticket->nomor)
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Tiket telah dibuka kembali oleh Administrator')
                    ->line('Nomor Tiket: ' . $this->ticket->nomor)
                    ->line('Judul: ' . $this->ticket->judul)
                    ->line('Departemen: ' . $this->ticket->departemen->nama_departemen)
                    ->action('Login untuk Proses Tiket', $url)
                    ->line('Terima kasih!');
    }
}
