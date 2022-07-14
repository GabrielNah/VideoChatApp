<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
////Public Routes
Route::post('/register',[\App\Http\Controllers\Api\V1\AuthController::class,'register'])->name('register');
Route::post('/login',[\App\Http\Controllers\Api\V1\AuthController::class,'login'])->name('login');




////Protected Routes
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get('/user',[\App\Http\Controllers\Api\V1\AuthController::class,'getLoggedInUser']);
    Route::get('/checkToken',[\App\Http\Controllers\Api\V1\AuthController::class,'checkToken']);
    Route::post('/logout',[\App\Http\Controllers\Api\V1\AuthController::class,'logout']);
    Route::get('/users',[\App\Http\Controllers\Api\V1\UsersDataController::class,'getAllUsers']);
    Route::post('/chat',[\App\Http\Controllers\Api\V1\UsersDataController::class,'getChatMessages']);
    Route::post('/message',[\App\Http\Controllers\Api\V1\UsersDataController::class,'appendInChat']);


});

