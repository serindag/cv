<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/image',[UserController::class,'image'])->name('image');
Route::post('/upload-images',[UserController::class,'uploadImages'])->name('upload-images');
