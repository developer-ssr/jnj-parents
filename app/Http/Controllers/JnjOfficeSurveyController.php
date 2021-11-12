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
                'jason1993tan@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'pearlsoptical@singnet.com.sg' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'eeqwtiffany@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'tsr729@hotmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'ejseow@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'jianing1278@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'Cfchau.jeremy@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'eyecarepeople@gmail.com' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'chewwk@videre-eyecare.com.sg' => ['jchng@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
            ],
            'us' => [
                'globalvisionrehabcenter@gmail.com' => ['AEvans1@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'juanm3@gmail.com' => ['AEvans1@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'gmoheep@gmail.com' => ['AEvans1@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'Michael.Le.od@gmail.com' => ['ANguye34@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'doc@brighteyestampa.com' => ['AEvans1@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'oaadoc@gmail.com' => ['ARahman4@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'citywideeye@gmail.com' => ['JPatino6@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'mengzdeng@gmail.com' => ['ANguye34@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'jenniferhsiehod@gmail.com' => ['ANguye34@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'dr.tan@compasseyecare.com' => ['ARahman4@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'christianh986@gmail.com' => ['AEvans1@its.jnj.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'carolinecho@sbcglobal.net' => ['ARahman4@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
                'sbutzon@gmail.com' => ['ARahman4@ITS.JNJ.com', 'LBeres@ITS.JNJ.com', 'jnj@splitsecondresearch.co.uk'],
            ]
        ];
        $survey = [
            'us' => [
                1 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/4fa/?id=' . $request->id,
                2 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/v8h/?id=' . $request->id
            ],
            'sg' => [
                1 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/XWp/?id=' . $request->id,
                2 => 'https://fluent.splitsecondsurveys.co.uk/engine/entry/N8N/1?id' . $request->id
            ]
        ];
        $s = $survey[$request->country][$request->survey];
        $data = collect($request->all())->merge(['survey' => $s, 'num' => $request->survey])->toArray();
        $email = $request->a2_2 ?? $request->h2_2;
        Mail::to($email)->send(new SurveyCompleted($data));
        $mails = $emails[$request->country][$email] ?? collect($email[$request->country])->first();
        Mail::to($mails[0])->send(new SurveyCompleted($data));
        Mail::to($mails[1])->send(new SurveyCompleted($data));
        Mail::to($mails[2])->send(new SurveyCompleted($data));
        return redirect("https://www.seeyourabiliti.com/professionals");
    }
}
