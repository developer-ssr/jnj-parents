<?php

namespace App\Mail;

use App\Models\Par;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $parent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Par $par)
    {
        $this->parent = $par;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.survey.created')
        ->subject("Myopia Study")
        ->with([
            'url' => "https://jnj.splitsecondsurveys.co.uk/parx/entry?id=" . $this->parent->uid
        ]);
    }
}
