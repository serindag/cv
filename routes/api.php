<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReferanceController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;




Route::group(['prefix'=>'auth','as'=>'auth'],function(){
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    
    Route::controller(UserController::class)->group(function(){
        Route::post('login','login');
        Route::post('register','register');

        Route::post('forgot-password','sendResetLinkEmail');
        Route::post('reset-password','resetPassword');
    });
    


});

Route::group(['middleware'=>['auth:sanctum','is_admin']],function(){

    // Hakkında

    Route::get('/about',[AboutController::class,'index']);
    Route::post('/about/update',[AboutController::class,'update']);


    // iletişim
    Route::get('/contact',[ContactController::class,'index']);
    Route::post('/contact/store',[ContactController::class,'store']);
    Route::post('/contact/mail/send',[ContactController::class,'mailSend']);

    //Kariyer
    Route::get('/careers',[CareerController::class,'index']);
    Route::post('/career/store',[CareerController::class,'store']);
    Route::post('/career/{id}/update',[CareerController::class,'update']);

    /* tag */
    Route::get('/tags',[TagController::class,'index']);
    Route::get('/tag/{id}',[TagController::class,'edit']);
    Route::post('/tag/store',[TagController::class,'store']);
    Route::post('/tag/{id}/update',[TagController::class,'update']);

    /* referance */
    Route::get('/referances',[ReferanceController::class,'index']);
    Route::get('/referance/{id}',[ReferanceController::class,'edit']);
    Route::post('/referance/store',[ReferanceController::class,'store']);
    Route::post('/referance/{id}/update',[ReferanceController::class,'update']);

    /* Category */
    Route::get('/categories',[CategoryController::class,'index']);
    Route::post('/category/store',[CategoryController::class,'store']);
    Route::post('/category/{id}/update',[CategoryController::class,'update']);

    /* Blog */
    Route::get('/blogs',[BlogController::class,'index']);
    Route::get('/blog/{id}',[BlogController::class,'edit']);
    Route::post('/blog/store',[BlogController::class,'store']);
    Route::post('/blog/{id}/update',[BlogController::class,'update']);

     

});

/* Project */
Route::get('/projects',[ProjectController::class,'index']);
Route::get('/project/{id}',[ProjectController::class,'edit']);
Route::post('/project/store',[ProjectController::class,'store']);
Route::post('/project/{id}/update',[ProjectController::class,'update']);