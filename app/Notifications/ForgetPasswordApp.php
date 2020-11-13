<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgetPasswordApp extends Notification
{
    use Queueable;
    protected $checkCode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($checkCode)
    {
        $this->checkCode = $checkCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Reset password request')
                ->from('ninjarestaurant@noreply.com', 'Ninja Restaurant')
                ->line('You have just requested to reset your password at Ninja Restaurant Mobile Application!')
                ->line('Your check code is '.$this->checkCode)
                ->line('Get check code and reset password at app.')
                ->action('See us in website', url('/index'))
                ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
