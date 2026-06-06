<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
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
Route::get('/jobs',[JobController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{id}',[JobController::class,'detail'])->name('jobs.detail');
Route::post('/apply-job',[JobController::class,'applyJob'])->name('jobs.apply');




Route::group(['prefix'=>'account'],function(){

          Route::group(['middleware'=>'guest'],function(){
            Route::get('/register',[AccountController::class,'registerIndex'])->name('account.register');
            Route::get('/login',[AccountController::class,'loginIndex'])->name('account.login');
            Route::post('/process-register',[AccountController::class,'registeration'])->name('account.registeration');
            Route::post('/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');

          });

          Route::group(['middleware'=>'auth'],function(){
            Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
            Route::get('/create-job',[AccountController::class,'createJob'])->name('account.createJob');
            Route::post('/save-job',[AccountController::class,'saveJob'])->name('account.savejob');
            Route::get('/my-jobs',[AccountController::class,'myJob'])->name('account.myjob');
            Route::get('/my-jobs/edit-jobs/{jobId}',[AccountController::class,'editJob'])->name('account.editJob');
            Route::put('/my-jobs/update-jobs/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
            Route::delete('/my-jobs/delete-jobs/{jobId}',[AccountController::class,'deleteJob'])->name('account.deleteJob');
            Route::post('/update-profile',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
            Route::put('/update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
            Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
          });
});
