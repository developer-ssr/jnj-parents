<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentScheduleToSurvey extends Mailable
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
            'ca' => 'Canada',
            'hk' => 'Hongkong'
        ];
        $links = [
            'us' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/nh1/1?",
            'sg' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/V84/1?",
            'hk' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/Mxg/1?",
            'ca' => "https://fluent.splitsecondsurveys.co.uk/engine/entry/9Ub/1?"
        ];
        return $this->markdown('emails.survey.parentscheduled')
            ->subject("J&J - Lacking days")
            ->with([
                'email' => $this->data['email'],
                'country' => $country[$this->data['country']],
                'due_date' => $this->data['due_date'],
                'lacking' => $this->data['lacking_days'],
                'link' => $links[$this->data['country']] . http_build_query(['email' => $this->data['email'], 'country' => $this->data['country']])
            ]);
    }
}
