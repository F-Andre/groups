<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Group;

class JoinGroupFalse extends Notification
{
  use Queueable;

  private $group;
  private $user;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Group $group)
  {
    $this->group = $group;
    $this->user = $user;
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
    $greeting = sprintf('Bonjour %s,', $notifiable->name);
    $line = sprintf("Votre demande pour rejoindre le groupe '%s' a été rejetée.", $this->group->name);
    $line2 = "Vous pouvez créer votre groupe ou faire une demande pour rejoindre un autre groupe.";
    return (new MailMessage)
      ->subject('Demande rejetée')
      ->greeting($greeting)
      ->line($line)
      ->line($line2)
      ->action('Accueil', url('/'))
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
