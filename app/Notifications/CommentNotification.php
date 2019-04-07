<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Post;

class CommentNotification extends Notification
{
  use Queueable;

  private $fromUser;
  private $post;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Post $post)
  {
    $this->fromUser = $user;
    $this->post = $post;
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
    $line = sprintf('%s vient de commenter votre article %s.', $this->fromUser->name, $this->post->titre);
    return (new MailMessage)
      ->subject('Nouveau commentaire')
      ->greeting($greeting)
      ->line($line)
      ->action('Voir l\'article', url('/'))
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
