<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function()
{

    Route::get('profile_view',[HomeController::class,'profile_view']);
    Route::get('logout',[HomeController::class,'logout']);
    Route::post('/add_task',[HomeController::class,'add_task']);
});
Route::post('login',[HomeController::class,'login']);
Route::post('create_user',[HomeController::class,'create_user']);


