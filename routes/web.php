<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[HomeController::class,'index'])->name('home');




Route::group(['account'],function(){

          Route::group(['middleware'=>'guest'],function(){
            Route::get('/account/register',[AccountController::class,'registerIndex'])->name('account.register');
            Route::get('/account/login',[AccountController::class,'loginIndex'])->name('account.login');
            Route::post('/account/process-register',[AccountController::class,'registeration'])->name('account.registeration');
            Route::post('/account/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');

          });

          Route::group(['middleware'=>'auth'],function(){
            Route::get('/account/profile',[AccountController::class,'profile'])->name('account.profile');
            Route::post('/account/update-profile',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
            Route::put('/account/update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
            Route::get('/account/logout',[AccountController::class,'logout'])->name('account.logout');
          });
});
