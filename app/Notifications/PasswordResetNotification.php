<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $port ="";

        if ($notifiable->role == 'Consommateur') { 
            $port = "3000";
        }else { 
            $port = "3001";
        }

        $urlToResetForm = "http://196.200.156.30:". $port."/changePasswordLink/?token=". $this->token;
        return (new MailMessage)
            ->subject(Lang::get('Réinitialisation de mot de passe'))
            ->line(Lang::get('Bonjour,'))
            ->line(Lang::get('Pour réinitialiser votre mot de passe myANOC, cliquez sur le lien suivant :'))
            ->action(Lang::get('Changer votre mot de passe'), $urlToResetForm)

            ->line(Lang::get('Ce lien de réinitialisation de mot de passe expirera dans :count minutes.', ['count' => config('auth.passwords.users.expire')]))
            // ->line(Lang::get('If you did not request a password reset, no further action is required. Token: ==>'. $this->token));
            ->line(Lang::get("Si vous ne souhaitez pas réinitialiser votre mot de passe, ignorez ce message ; quelqu'un a probablement inséré votre adresse e-mail par erreur."))
            ->line(Lang::get('Merci.'))
            ->line(Lang::get('ANOC'))
            ->line(Lang::get("Association Nationale des Ovins et Caprins"))
            
            ->line(Lang::get("http://www.anoc.ma"));
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
