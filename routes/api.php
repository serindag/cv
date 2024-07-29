<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReferanceController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Models\SiteSetting;


Route::group(['prefix'=>'auth','as'=>'auth'],function(){
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    
     
        Route::post('login',[UserController::class,'login']);
        Route::post('register', [UserController::class,'register']);
        Route::post('forgot-password', [UserController::class,'sendResetLinkEmail']);
        Route::post('reset-password', [UserController::class,'resetPassword']);
  
    


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

   

    /* Project */
    Route::get('/projects',[ProjectController::class,'index']);
    Route::get('/project/{id}',[ProjectController::class,'edit']);
    Route::post('/project/store',[ProjectController::class,'store']);
    Route::post('/project/{id}/update',[ProjectController::class,'update']);

      //Kariyer
    Route::get('/educations',[EducationController::class,'index']);
    Route::post('/education/store',[EducationController::class,'store']);
    Route::post('/education/{id}/update',[EducationController::class,'update']);

    /* slider */
    Route::get('/sliders',[SliderController::class,'index']);
    Route::get('/slider/{id}',[SliderController::class,'edit']);
    Route::post('/slider/store',[SliderController::class,'store']);
    Route::post('/slider/{id}/update',[SliderController::class,'update']);

    /* Social Media */
    Route::get('/socialMedias',[SocialMediaController::class,'index']);
    Route::get('/socialMedia/{id}',[SocialMediaController::class,'edit']);
    Route::post('/socialMedia/store',[SocialMediaController::class,'store']);
    Route::post('/socialMedia/{id}/update',[SocialMediaController::class,'update']);
    Route::post('/socialMedia/sortable',[SocialMediaController::class,'order']);

});


/* Site Setting */
Route::get('/siteSettings',[SiteSettingController::class,'index']);
Route::get('/siteSetting/{id}',[SiteSettingController::class,'edit']);
Route::post('/siteSetting/store',[SiteSettingController::class,'store']);
Route::post('/siteSetting/{id}/update',[SiteSettingController::class,'update']);

 /* Blog */
 Route::get('/blogs',[BlogController::class,'index']);
 Route::get('/blog/{id}',[BlogController::class,'edit']);
 Route::post('/blog/store',[BlogController::class,'store']);
 Route::post('/blog/{id}/update',[BlogController::class,'update']);