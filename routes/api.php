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
    Route::post('add_task',[HomeController::class,'add_task']);
    Route::get('view_task',[HomeController::class,'view_task']);
    Route::post('update_task/{taskId}',[HomeController::class,'update_task']);
    Route::post('delete_task/{taskId}',[HomeController::class,'delete_task']);
    Route::get('select/{taskId}',[HomeController::class,'select']);

});
Route::post('login',[HomeController::class,'login']);
Route::post('create_user',[HomeController::class,'create_user']);
Route::get('view_user',[HomeController::class,'view_user']);
Route::get('/view_tasks',[HomeController::class,'view_tasks']);



