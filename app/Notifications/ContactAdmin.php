<?php

namespace App\Notifications;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactAdmin extends Notification
{
  use Queueable;

  protected $user;
  protected $group;
  protected $subject;
  protected $message;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Group $group, User $user, $subject, $message)
  {
    $this->group = $group;
    $this->user = $user;
    $this->subject = $subject;
    $this->message = $message;
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
    $subject = sprintf('Message de %s', $this->user->name);
    $greeting = sprintf('Bonjour %s,', $notifiable->name);
    $line = sprintf('%s vous a envoyé ce message concernant le groupe "%s":', $this->user->name, $this->group->name);
    return (new MailMessage)
      ->subject($subject)
      ->greeting($greeting)
      ->line($line)
      ->line('Sujet:')
      ->line($this->subject)
      ->line('Message:')
      ->line($this->message)
      ->line('Ce message n\'est pas enregistré sur le site. Pour le sauvegarder, conservez ce mail.')
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
