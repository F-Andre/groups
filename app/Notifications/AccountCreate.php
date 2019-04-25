<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class AccountCreate extends Notification
{
  use Queueable;

  private $name;
  private $email;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($name, $email)
  {
    $this->name = $name;
    $this->email = $email;
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
    $greeting = sprintf('Bonjour %s', $notifiable->name);
    $line = sprintf('%s vient de créer un compte avec l\'adresse email %s', $this->name, $this->email);
    return (new MailMessage)
      ->subject('Nouveau compte')
      ->greeting($greeting)
      ->line($line)
      ->action('Voir le site', url('/blog'))
      ->line('Merci et à bientôt!');
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
