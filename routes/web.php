<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YatzyController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;

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
        'title' => "Home | YatzyBonanza"
    ]);
});

Route::get('/yatzy', [YatzyController::class, 'start']);
Route::post('/yatzy', [YatzyController::class, 'play']);

Route::get('/highscores', [ResultsController::class, 'highScores']);
Route::post('/highscores', [ResultsController::class, 'submitResult']);

Route::get('/myaccount', [MyAccountController::class, 'start']);

Route::get('/register', [RegistrationController::class, 'create']);
Route::post('/register', [RegistrationController::class, 'store']);

Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::get('/logout', [SessionController::class, 'destroy']);
