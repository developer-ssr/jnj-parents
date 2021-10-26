<?php

use App\Http\Controllers\ProcessController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

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
    $data = [
        'q1' => 'crisjohnreytarpin@gmail.com',
        'q2' => '14/10/2021',
        'q3' => 'Casisang clinic'
    ];
    $visited = Carbon::parse('October 19, 2021');
    dd($visited->diffInDays(now(), false));
    //return redirect('http://jj-parents.test/process?' . http_build_query($data));
});

Route::get('/check', [ProcessController::class, 'lack']);

//via email entry link
Route::get('entry', [ProcessController::class, 'entry']);

//via QR code 2
Route::get('entry2', [ProcessController::class, 'entry2']);

Route::get('/complete', [ProcessController::class, 'complete']);
Route::get('/so', [ProcessController::class, 'so']);