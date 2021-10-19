<?php

namespace App\Http\Controllers;

use App\Mail\SurveyCreated;
use App\Models\Par;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;

class ProcessController extends Controller
{

    private $step2_links = [
        'sg' => 'V84'
    ];

    private $step1_links = [
        'sg' => 'FKC'
    ];

    public function index(Request $request)
    {
        $email = $request->q1;
        $date = Carbon::parse($request->q2);
        $clinic = $request->q3;

        $parent = Par::create([
            'uid' => Uuid::uuid4(),
            'email' => $email,
            'visited_at' => $date,
            'clinic' => $clinic,
            'info' => $this->getClientInfo($request),
            'country' => $request->country
        ]);

        Mail::to($email)->send(new SurveyCreated($parent));
        return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step1_links[$request->country]}?" . http_build_query($request->all()));
    }

    private function getClientInfo(Request $request)
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        return collect([
            'ip' => $request->ip(),
            'browser' => $browser . " " . $agent->version($browser),
            'device' => ($agent->isDesktop() ? 'Desktop ' : 'Mobile ') . $agent->device(),
            'platform' => $platform . " " . $agent->version($platform),
            'url' => $request->fullUrl()
        ]);
    }

    public function entry(Request $request)
    {
        $id = $request->id;
        $parent = Par::where('uid', $id)->first();
        $days = Carbon::parse($parent->visited_at)->diffInDays(now(), false);
        $lacking_days = 14 - $days;
        if ($lacking_days <= 0) {
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/entry/{$this->step2_links[$parent->country]}/?id={$id}&qr=1&" . http_build_query([
                'email' => $parent->email,
                'clinic' => $parent->clinic,
                'visited' => $parent->visited_at
            ]));
        } else {
            return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&preview=1&id={$id}&days={$lacking_days}");
        }
    }


    // via QR code 2
    public function entry2(Request $request)
    {
        $email = $request->q1;
        $parent = Par::where('email', $email)->orderBy('id', 'desc')->first();
        $id = $parent->uid;
        $days = Carbon::parse($parent->visited_at)->diffInDays(now(), false);
        $lacking_days = 14 - $days;
        if ($lacking_days <= 0) {
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step2_links[$parent->country]}/?id={$id}&" . http_build_query([
                'email' => $parent->email,
                'clinic' => $parent->clinic,
                'visited' => $parent->visited_at
            ]));
        } else {
            return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&preview=1&id={$id}&days={$lacking_days}");
        }
    }

    public function complete(Request $request)
    {
        $id = $request->id;
        $parent = Par::where('uid', $id)->first();
        $parent->update([
            'is_complete' => true
        ]);
        return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step2_links[$parent->country]}?" . http_build_query($request->all()));
    }

    public function lack($id, $days = 2)
    {
        return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&id={$id}&days={$days}");
    }
}
