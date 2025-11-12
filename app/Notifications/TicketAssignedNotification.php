<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketAssignedNotification extends Notification
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
                    ->subject('Tiket Ditugaskan Kepada Anda - #' . $this->ticket->nomor)
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Tiket telah ditugaskan kepada Anda')
                    ->line('Nomor Tiket: ' . $this->ticket->nomor)
                    ->line('Judul: ' . $this->ticket->judul)
                    ->line('Departemen: ' . $this->ticket->departemen->nama_departemen)
                    ->line('Urgency: ' . $this->ticket->urgency->urgency)
                    ->line('Deadline: ' . $this->ticket->tanggal_selesai->format('d/m/Y H:i'))
                    ->action('Login untuk Kerjakan Tiket', $url)
                    ->line('Terima kasih!');
    }
}
