<?php

use App\Http\Controllers\API\Expert\AuthExpertController;
use App\Http\Controllers\API\Expert\AvailableTimeController;
use App\Http\Controllers\API\Expert\ExpertController;
use App\Http\Controllers\API\User\AppointmentController;
use App\Http\Controllers\API\User\EvaluationController;
use App\Http\Controllers\API\User\FavouriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Expert\ConsultationController;
use App\Http\Controllers\API\User\AuthUserController;
use App\Http\Controllers\API\User\UserController;
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

//---------------------User Auth-----------------------

Route::group(['prefix' => 'user'], function(){
    Route::post('/register',[AuthUserController::class, 'register']);
    Route::post('/login',[AuthUserController::class, 'userLogin']);
});

//---------------------Expert Auth-----------------------

Route::group(['prefix' => 'expert'], function(){
    Route::post('/register',[AuthExpertController::class, 'register']);
    Route::post('/login',[AuthExpertController::class, 'expertLogin']);
});

//---------------------User Authorization-----------------------

Route::group(['prefix'=>'user','middleware' => ['auth:user-api','scopes:user']], function() {
    Route::post('/logout',[AuthUserController::class,'userLogout']);
    Route::get('/returnExperts',[UserController::class,'RetrievingExpertConsultation']);
    Route::get('/search/{name}',[UserController::class,'searchName']);
    Route::get('/searchCon/{consultation}',[UserController::class,'searchConsultation']);
    Route::get('/show/{id}',[UserController::class,'show']);
    Route::get('/showAvailableTime',[AvailableTimeController::class,'Show']);
    Route::post('/storeAppointmentTime',[AppointmentController::class,'store']);
    Route::post('/update',[UserController::class,'update']);
    Route::post('/favourite',[FavouriteController::class,'store']);
    Route::post('/showFavourite',[FavouriteController::class,'show']);
    Route::get('/myProfile',[UserController::class,'myProfile']);
    Route::post('/evaluation',[EvaluationController::class,'store']);
    Route::post('/updateRate',[EvaluationController::class,'update']);
    Route::post('/finishRate',[EvaluationController::class,'retrievingExpertRating']);


    //Route::get('/index',[UserController::class,'index']);
});

//---------------------Expert Authorization-----------------------
Route::post('/consultation',[ConsultationController::class,'store']);
Route::post('/store_consultation',[ConsultationController::class,'store']);


Route::group(['prefix'=>'expert','middleware' => ['auth:expert-api','scopes:expert']], function() {
    Route::post('/logout',[AuthUserController::class,'expertLogout']);
    Route::post('/update',[ExpertController::class,'update']);
    Route::post('/storeAvailableTime',[AvailableTimeController::class,'store']);
    Route::post('/cons',[ExpertController::class,'storeConsultaion']);
    Route::get('/showAppointment',[AppointmentController::class,'show']);
    Route::get('/myProfile',[ExpertController::class,'myProfile']);
    Route::post('/storeAppointmentTime',[AppointmentController::class,'store']);
    Route::post('/evaluation',[EvaluationController::class,'store']);

    // Route::post('/store',[AppointmentController::class,'store']);



    //  Route::get('/index',[\App\Http\Controllers\API\Expert\ExpertController::class,'index']);


});
