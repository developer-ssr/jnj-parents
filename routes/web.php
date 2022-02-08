<?php

use App\Http\Controllers\JnjOfficeSurveyController;
use App\Http\Controllers\ProcessController;
use App\Mail\EmailSent;
use App\Mail\ParentCompleted;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/process', [ProcessController::class, 'index']);
Route::get('/test', function() {
    // $data = [
    //     'q1' => 'crisjohnreytarpin@gmail.com',
    //     'q2' => '14/10/2021',
    //     'q3' => 'Casisang clinic'
    // ];
    // $visited = Carbon::parse('October 19, 2021');
    // dd($visited->diffInDays(now(), false));
    //return redirect('http://jj-parents.test/process?' . http_build_query($data));
    $array = [
        'a2_1' => "Cris john rey tarpin",
        'survey' => 1,
        'email' => "crisjohnreytarpin@gmail.com"
    ];
    return "http://jnj.splitsecondsurveys.co.uk/parx/ecp/complete?" . http_build_query($array);
});

Route::get('/check', [ProcessController::class, 'lack']);

//via email entry link
Route::get('entry', [ProcessController::class, 'entry']);

//via QR code 2
Route::get('entry2', [ProcessController::class, 'entry2']);

Route::get('/complete', [ProcessController::class, 'complete']);
Route::get('/so', [ProcessController::class, 'so']);

Route::get('/ecp/complete', [JnjOfficeSurveyController::class, 'sendEmail']);

Route::get('/email-sent', function(Request $request) {
    Mail::to('cris.tarpin@splitsecondsoftware.com')->send(new EmailSent($request->all()));
    Mail::to('geraldine.trufil@splitsecondresearch.co.uk')->send(new EmailSent($request->all()));
    // Mail::to('lberes@its.jnj.com')->send(new EmailSent($request->all()));
    return redirect('https://www.seeyourabiliti.com');
});
Route::get('/parent-completed', function (Request $request) {
    Mail::to('cris.tarpin@splitsecondsoftware.com')->send(new ParentCompleted($request->all()));
    Mail::to('geraldine.trufil@splitsecondresearch.co.uk')->send(new ParentCompleted($request->all()));
    // Mail::to('lberes@its.jnj.com')->send(new ParentCompleted($request->all()));
    return redirect('https://www.seeyourabiliti.com');
});