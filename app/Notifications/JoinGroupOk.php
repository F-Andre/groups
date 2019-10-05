<?php

namespace App\Notifications;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinGroupOk extends Notification
{
  use Queueable;

  private $user;
  private $group;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Group $group)
  {
    $this->user = $user;
    $this->group = $group;
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
    $line = sprintf("Votre demande pour rejoindre le groupe '%s' a été acceptée.", $this->group->name);
    $line2 = "Cliquez sur le bouton pour publier un article.";
    return (new MailMessage)
      ->subject('Demande acceptée')
      ->greeting($greeting)
      ->line($line)
      ->line($line2)
      ->action($this->group->name, url('/' . $this->group->name . '/posts'))
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
