<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;
    public $email = '';
    public $confirm_code = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $confirm_code)
    {
        $this->email = $email;
        $this->confirm_code = $confirm_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emailVerify');
    }
}
