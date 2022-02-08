<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailSent extends Mailable
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
            'sg' => 'Singapore',
            'hk' => 'Hongkong',
            'ca' => 'Canada'
        ];

        $links = [
            'us' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/nh1/1?",
            'sg' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/V84/1?",
            'hk' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/Mxg/1?",
            'ca' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/9Ub/1?"
        ];
        return $this->markdown('emails.survey.email')
            ->subject("J&J")
            ->with([
                'email' => $this->data['q1'],
                'country' => $country[$this->data['country']],
                'link' => $links[$this->data['country']] . http_build_query(['email' => $this->data['q1']])
            ]);
    }
}
