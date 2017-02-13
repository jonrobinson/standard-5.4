<?php
namespace App\Mailers;

use App\Mailers\Mailer;
use App\Models\User;

class UserMailer extends Mailer
{
    public function registered(User $user)
    {
        $subject    = 'Welcome to <Company Name>';
        $view       = 'mail.user.registered';
        $data       = [
            'user' => $user
        ];
        $this->emailTo($user, $view, $data, $subject);
    }

    public function confirmation(User $user)
    {
        $subject    = 'Confirm your <Company Name> email';
        $view       = 'mail.user.confirmation';
        $data       = [
            'user' => $user
        ];
        $this->emailTo($user, $view, $data, $subject);
    }
}
