<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pollController;
use App\Http\Controllers\HomeController;

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
    return view('main');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/createPoll', [pollController::class, 'createPoll']);
Route::get('/poll', [pollController::class, 'getPoll'])->name('poll');
Route::post('/vote', [pollController::class, 'vote']);
Route::get('/my_polls', [pollController::class, 'userPolls'])->name('my_polls');
Route::post('/follow', [pollController::class, 'followPolls'])->name('follow');
Route::get('/follow_polls', [pollController::class, 'getFollowPolls'])->name('follow_polls');