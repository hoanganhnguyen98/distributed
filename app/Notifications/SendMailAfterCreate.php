<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailAfterCreate extends Notification
{
    use Queueable;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        // Below, Return in web with route
        // return (new MailMessage)
        //             ->line('You have just been registered for an account on '.env('APP_NAME').'!')
        //             ->line('Your account is this email.')
        //             ->line('With password: '.$this->password)
        //             ->action('Click to login', url('/'))
        //             ->line('Thank you for using our application!');

        // Here, return in api with app
        return (new MailMessage)
                ->subject('Thanks for registering')
                ->from('ninjarestaurant@noreply.com', 'Ninja Restaurant')
                ->line('You have just been registered for an account on Ninja Restaurant Mobile Application!')
                ->line('Now, you can login with account as your email and password.')
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
