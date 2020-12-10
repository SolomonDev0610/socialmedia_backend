<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $sender_email = '';
    public $fullname = '';
    public $subject = '';
    public $sender_message = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sender_email, $fullname, $subject, $sender_message)
    {
        $this->sender_email = $sender_email;
        $this->fullname = $fullname;
        $this->subject = $subject;
        $this->sender_message = $sender_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('contactUs');
    }
}
