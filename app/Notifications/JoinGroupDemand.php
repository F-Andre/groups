<?php

namespace App\Notifications;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinGroupDemand extends Notification
{
  use Queueable;

  private $group;
  private $user;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Group $group, User $user)
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
    $line = sprintf('%s demande a rejoindre votre groupe : %s.', $this->user->name, $this->group->name);
    $line2 = "Pour accepter ou refuser la demande, rendez-vous dans l'espace administration de votre groupe.";
    return (new MailMessage)
      ->subject('Demande d\'adhésion')
      ->greeting($greeting)
      ->line($line)
      ->line($line2)
      ->action('Administration de ' . $this->group->name, url('/' . $this->group->name . '/admin'))
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
