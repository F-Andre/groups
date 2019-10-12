<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Group;
use App\User;

class DeregisterUser extends Notification
{
  use Queueable;

  protected $user;
  protected $group;
  protected $reason;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Group $group, User $user, $reason)
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
    $line = sprintf('%s a été radié du groupe : %s.',$this->user->name ,$this->group->name);
    $line2 = sprintf("La raison donnée pour cette radiation est: '%s'", $this->reason);
    return (new MailMessage)
      ->subject('Radiation du groupe ' . $this->group->name)
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
