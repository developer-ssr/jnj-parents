<?php

namespace App\Http\Controllers;

use App\Mail\SurveyCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class JnjOfficeSurveyController extends Controller
{
    public function sendEmail(Request $request)
    {
        $country = $request->country;
        $emails = [
            'sg' => [
                'jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'
            ]
        ];
        $survey = [
            1 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/XWp/?id=' . $request->id,
            2 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/XWp/?id=' . $request->id
        ];
        $data = collect($request->all())->merge(['survey' => $survey[$request->survey], 'num' => $request->survey])->toArray();
        Mail::to($request->a2_2 ?? $request->h2_2)->send(new SurveyCompleted($data));
        $mails = $emails[$request->country];
        // Mail::to($mails[0])->send(new SurveyCompleted($data));
        // Mail::to($mails[1])->send(new SurveyCompleted($data));
        // Mail::to($mails[2])->send(new SurveyCompleted($data));
        return redirect("https://www.seeyourabiliti.com/professionals");
    }
}
