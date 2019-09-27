<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Group;
use App\User;

class WarnUserNotif extends Notification
{
  use Queueable;

  private $group;
  private $user;
  private $reason;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Group $group, $reason)
  {
    $this->group = $group;
    $this->user = $user;
    $this->reason = $reason;
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
    $line = sprintf("L'administrateur du groupe '%s' vous a envoyé un avertissement.", $this->group->name);
    $line2 = sprintf("La raison de cet avertissement est: '%s'", $this->reason);
    
    return (new MailMessage)
      ->subject('Avertissement')
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
