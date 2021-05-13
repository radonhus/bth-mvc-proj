<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YatzyController;

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

Route::get('/', function () {
    return view('start', [
        'title' => "Home | DiceLaVerdad"
    ]);
});

Route::get('/yatzy', [YatzyController::class, 'start']);
Route::post('/yatzy', [YatzyController::class, 'play']);

Route::get('/yatzyScore', [YatzyController::class, 'highScores']);
Route::post('/yatzyScore', [YatzyController::class, 'submitHighScore']);
