<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YatzyController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\AccountController;
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

Route::get('/', [SessionController::class, 'start']);
Route::get('/login', [SessionController::class, 'start'])->name('login');
Route::post('/login', [SessionController::class, 'verifyCreate']);
Route::get('/logout', [SessionController::class, 'logoutDestroy'])->middleware('auth');

Route::get('/register', [AccountController::class, 'start']);
Route::post('/register', [AccountController::class, 'verifySave']);
Route::post('/myaccount', [AccountController::class, 'denyChallenge'])->middleware('auth');
Route::get('/myaccount', [AccountController::class, 'myAccount'])->name('myaccount')->middleware('auth');

Route::get('/gamemode', [YatzyController::class, 'gamemode'])->middleware('auth');
Route::post('/yatzysetup', [YatzyController::class, 'setup'])->middleware('auth');
Route::post('/yatzyplay', [YatzyController::class, 'play'])->middleware('auth');
Route::get('/yatzyview', [YatzyController::class, 'yatzyview'])->name('yatzyview')->middleware('auth');

Route::post('/highscores', [ResultsController::class, 'submitResult'])->middleware('auth');
Route::get('/highscores', [ResultsController::class, 'highScores'])->name('highscores');

Route::get('/result/{id}', function ($id) {
    $ctrl = new ResultsController();
    return $ctrl->oneResult($id);
});

Route::get('/challenge/{id}', function ($id) {
    $ctrl = new ResultsController();
    return $ctrl->oneChallenge($id);
})->name('challenge');
