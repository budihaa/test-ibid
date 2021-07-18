<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendEmail extends Mailable
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email');
    }
}
