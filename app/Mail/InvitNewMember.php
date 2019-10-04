<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Group;
use App\User;

class InvitNewMember extends Mailable
{
  use Queueable, SerializesModels;

  private $group;
  private $user;
  private $mail_to;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Group $group, User $user, $mail_to)
  {
    $this->group = $group;
    $this->user = $user;
    $this->mail_to = $mail_to;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject = sprintf('%s vous invite à rejoindre son groupe', $this->user->name);
    $actionUrl = url('/');

    return $this->from('contact@fabienandre.com','Groups')
      ->to($this->mail_to)
      ->subject($subject)
      ->markdown('invit.new.members')
      ->with(['name' => $this->user->name, 'groupName' => $this->group->name, 'actionUrl' => $actionUrl]);
  }
}
