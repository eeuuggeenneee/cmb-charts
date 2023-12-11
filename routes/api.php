<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\RealTimeChart;
use App\Http\Livewire\XAcc;
use App\Http\Livewire\ZAcc;
use App\Http\Livewire\XVel;
use App\Http\Livewire\ZVel;
/*

|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/sensor-data/temp/{sensor}', [RealTimeChart::class, 'getSensorData']);
Route::get('/sensor-data/x-acc/{sensor}', [XAcc::class, 'getSensorData']);
Route::get('/sensor-data/z-acc/{sensor}', [ZAcc::class, 'getSensorData']);
Route::get('/sensor-data/x-vel/{sensor}', [XVel::class, 'getSensorData']);
Route::get('/sensor-data/z-vel/{sensor}', [XVel::class, 'getSensorData']);