<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentCompleted extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $country = [
            'us' => 'United States of America',
            'sg' => 'Singapore'
        ];
        return $this->markdown('emails.survey.parentcompleted')
            ->subject("J&J")
            ->with([
                'email' => $this->data['email'],
                'country' => $country[$this->data['country']],
            ]);
    }
}
