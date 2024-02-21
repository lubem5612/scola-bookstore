<?php


namespace Transave\ScolaBookstore\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDeliveryStatusChangeNotification extends Notification
{
    use Queueable;

    public $emailData;

    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
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
        $user = $this->emailData["user"];
        $token = $this->emailData["token"];

        return (new MailMessage)
            ->subject("New Order Received")
            ->line("Hello {$user->first_name}")
            ->line("You have requested for a password reset")
            ->line("Please, ignore this message if you did not initiate this request.")
            ->line("In order to reset your password, use the OTP below to reset your password")
            ->line("$token")
            ->line("If you have any complaints please contact our support.")
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