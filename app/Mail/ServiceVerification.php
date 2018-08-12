<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject = "Verification";
    public $type;
    public $name = "do-not-reply";
    public $data;
    public function __construct(\App\ServiceVerification $verification)
    {
        $this->data = $verification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify-services-email')
            ->subject($this->subject)
            ->from('do-not-reply@servizone.net', $this->name);
    }
}
