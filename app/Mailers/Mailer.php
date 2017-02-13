<?php
namespace App\Mailers;

use App\Jobs\SendMail;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class Mailer
{
    use DispatchesJobs;

    public function emailTo($person, $view, $data, $subject)
    {
        $this->dispatch(new SendMail($view, $data, $person, $subject));
    }
}
