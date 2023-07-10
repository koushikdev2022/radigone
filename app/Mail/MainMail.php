<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MainMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dt;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dt)
    {
        $this->dt = $dt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->dt;
        return $this->subject($data['subject'])->view('email.main');
    }
}
