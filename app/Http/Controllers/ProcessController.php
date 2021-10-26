<?php

namespace App\Http\Controllers;

use App\Mail\SurveyCreated;
use App\Models\Par;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;

class ProcessController extends Controller
{

    private $step2_links = [
        'sg' => 'V84',
        'us' => 'nh1'
    ];

    private $step1_links = [
        'sg' => 'FKC',
        'us' => 'Sm8'
    ];

    private $step3_links = [
        'sg' => '',
        'us' => '4NL'
    ];

    public function index(Request $request)
    {
        $email = $request->q15 ?? null;
        $date = Carbon::createFromFormat('d/m/Y', $request->q14);
        //$clinic = $request->q3;

        $parent = Par::create([
            'uid' => $request->id,
            'email' => $email,
            'visited_at' => $date,
            //'clinic' => $clinic,
            'info' => $this->getClientInfo($request),
            'country' => $request->country
        ]);
        if (!is_null($email)) {
            Mail::to($email)->send(new SurveyCreated($parent));
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step1_links[$request->country]}?" . http_build_query($request->all()));
        } else {
            Http::get("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step1_links[$request->country]}?" . http_build_query($request->all()));
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/entry/{$this->step2_links[$parent->country]}/?id={$request->id}&qr=1&" .http_build_query([
                'visited' => $parent->visited_at
            ]));
        }
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
        ])->merge($request->all())->toArray();
    }

    public function entry(Request $request)
    {
        $id = $request->id;
        $parent = Par::where('uid', $id)->first();
        if ($parent->is_complete) {
            return redirect("https://www.seeyourabiliti.com");
        }
        $days = Carbon::parse($parent->visited_at)->diffInDays(now(), false);
        $lacking_days = 14 - $days;
        if ($lacking_days <= 0) {
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/entry/{$this->step3_links[$parent->country]}/?id={$id}&source=email&" . http_build_query([
                'email' => $parent->email,
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
        $id = $request->id;

        $parent = Par::where('email', $email)->orderBy('id', 'desc')->first();
        if (is_null($parent)) {
            $parent = Par::where('uid', $id)->first();
            if (is_null($parent)) {
                return redirect("https://express.splitsecondsurveys.co.uk/engine?code=dGerRlvJYR&ID={$ID}");
            }
            $parent->update([
                'email' => $email
            ]);
            Http::get("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step2_links[$parent->country]}?id={$request->id}");
            return redirect("https://fluent.splitsecondsurveys.co.uk/engine/entry/{$this->step3_links[$parent->country]}/?id={$request->id}&qr=1&" . http_build_query([
                'email' => $parent->email,
                'visited' => $parent->visited_at
            ]));
        } else {
            if ($parent->is_complete) {
                return redirect("https://www.seeyourabiliti.com");
            }
            $days = Carbon::parse($parent->visited_at)->diffInDays(now(), false);
            $lacking_days = 14 - $days;
            if ($lacking_days <= 0) {
                Http::get("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step2_links[$parent->country]}?id={$request->id}");
                return redirect("https://fluent.splitsecondsurveys.co.uk/engine/entry/{$this->step3_links[$parent->country]}?id={$parent->uid}&" . http_build_query([
                    'email' => $parent->email,
                    'visited' => $parent->visited_at,
                    'qr' => 2
                ]));
            } else {
                return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&id={$id}&days={$lacking_days}");
            }
        }

        // $parent = $email ? Par::where('email', $email)->orderBy('id', 'desc')->first() : null;
        // $id = $parent->uid;
        // if ($parent->is_complete) {
        //     return redirect("https://www.seeyourabiliti.com");
        // }
        // $days = Carbon::parse($parent->visited_at)->diffInDays(now(), false);
        // $lacking_days = 14 - $days;
        // if ($lacking_days <= 0) {
        //     $parent->update([
        //         'info' => collect($parent->info)->merge(['id' => $request->id])
        //     ]);
        //     return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step2_links[$parent->country]}/?id={$request->id}&" . http_build_query([
        //         'email' => $parent->email,
        //         //'clinic' => $parent->clinic,
        //         'visited' => $parent->visited_at
        //     ]));
        // } else {
        //     return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&preview=1&id={$id}&days={$lacking_days}");
        // }
    }

    public function complete(Request $request)
    {
        $email = $request->email;
        $parent = Par::where('email', $email)->first();
        $parent->update([
            'is_complete' => true
        ]);
        return redirect("https://fluent.splitsecondsurveys.co.uk/engine/complete/{$this->step3_links[$parent->country]}?" . http_build_query($request->all()));
    }

    public function lack($id, $days = 2)
    {
        return redirect("https://express.splitsecondsurveys.co.uk/engine/?code=fxOmcL12MF&id={$id}&days={$days}");
    }
}
