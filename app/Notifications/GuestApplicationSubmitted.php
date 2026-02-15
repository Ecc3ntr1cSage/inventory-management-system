<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Application;

class GuestApplicationSubmitted extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $app = $this->application;

        return (new MailMessage)
                    ->subject('New Guest Asset Request')
                    ->greeting('Hello')
                    ->line('A new guest asset request has been submitted.')
                    ->line('Name: ' . ($app->guest_name ?? 'N/A'))
                    ->line('Email: ' . ($app->guest_email ?? 'N/A'))
                    ->line('Description: ' . ($app->description ?? ''))
                    ->action('View Submissions', url(route('asset.submission')))
                    ->line('You can review and approve the request in the submissions panel.');
    }
}
