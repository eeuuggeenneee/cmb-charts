<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\RealTimeChart;
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
    return view('welcome');
})->name('home');

Route::get('/x-velocity', function () {
    return view('xvel');
})->name('xvel');

Route::get('/z-velocity', function () {
    return view('zvel');
})->name('zvel');

Route::get('/x-acceleration', function () {
    return view('xacc');
})->name('xacc');

Route::get('/z-acceleration', function () {
    return view('zacc');
})->name('zacc');




