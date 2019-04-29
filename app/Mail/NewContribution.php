<?php

namespace App\Mail;


use App\User;
use App\Contribution;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewContribution extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $con;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Contribution $con)
    {
        //
        $this->user = $user;
        $this->con = $con;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $data['user'] = $user;
        return $this->view('emails.newcon');
    }
}
