<?php

use App\Http\Controllers\accountController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\jobApplicationController;
use App\Http\Controllers\admin\jobController;
use App\Http\Controllers\admin\userController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\jobsConroller;
use App\Models\job;
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

Route::get('/', function () {
    return view('welcome');
});

// ********************these routes are used in group middleware************************

// Route::get("home-page/",[homeController::class,"index"])->name("home.page");
// Route::get("/account/register",[accountController::class,"registration"])->name("account.register");
// Route::POST("/account/register-process",[accountController::class,"registerProcess"])->name("account.registerProcess");

// Route::get("/account/login",[accountController::class,"login"])->name("account.login");
// Route::POST("/account/authentication",[accountController::class,"authentication"])->name("account.authentication");

// Route::GET("/account/profile",[accountController::class,"profile"])->name("account.profile");
// Route::GET("/account/logout",[accountController::class,"logout"])->name("account.logout");



Route::get("home-page/",[homeController::class,"index"])->name("home.page");
Route::get("job-page/",[jobsConroller::class,"index"])->name("home.job");
Route::get("job-detail/{id}",[jobsConroller::class,"jobdetail"])->name("job.jobdetail");
Route::POST("job-apply/",[jobsConroller::class,"jobapply"])->name("job.jobapply");
Route::POST("save-job/",[jobsConroller::class,"saveJob"])->name("job.saveJob");


Route::group(['prefix'=>'admin' , 'middleware'=> 'checkRole'],function(){

    Route::GET("/admin-dashboard",[dashboardController::class,"index"])->name("admin.dashboard");  
    Route::GET("/user",[userController::class,"index"])->name("admin.list"); 
    Route::GET("/user-edit/{id}",[userController::class,"edit"])->name("admin.user.edit");
    Route::POST("/user-update/{id}",[userController::class,"update"])->name("admin.user.update");  
    Route::delete("/user-delete",[userController::class,"destroy"])->name("admin.user.destroy"); 
    Route::GET("/user-jobs",[jobController::class,"index"])->name("admin.jobs.list");   
    Route::GET("/user-jobs-edit/{id}",[jobController::class,"edit"])->name("admin.user.jobs.edit");  
    Route::POST("/user-jobs-update/{id}",[jobController::class,"update"])->name("admin.user.jobs.update");  
    Route::delete("/user-jobs-delete",[jobController::class,"destroy"])->name("admin.user.jobs.delete");
    Route::GET("/user-jobapplication",[jobApplicationController::class,"index"])->name("admin.user.jobapplication");
    Route::delete("/user-jobapplication/delete",[jobApplicationController::class,"destroy"])->name("admin.user.jobapplication.destroy");



  
 


});




Route::group(['prefix'=>'account'],function(){

    Route::group(['middleware'=>'guest'],function(){
        
        // guest
        Route::get("/register",[accountController::class,"registration"])->name("account.register");
        Route::POST("/register-process",[accountController::class,"registerProcess"])->name("account.registerProcess");
        Route::get("/login",[accountController::class,"login"])->name("account.login");
        Route::POST("/authentication",[accountController::class,"authentication"])->name("account.authentication");
    });

    

        // auth
    Route::group(['middleware'=>'auth'],function(){
    Route::GET("/profile",[accountController::class,"profile"])->name("account.profile");
    Route::POST("/update-profile",[accountController::class,"updateProfile"])->name("account.updateProfile");
    Route::POST("/change-profile-pic",[accountController::class,"changeProfilePic"])->name("account.changeProfilePic");
    Route::GET("/logout",[accountController::class,"logout"])->name("account.logout");
    Route::GET("/create-job",[accountController::class,"createJob"])->name("account.createJob");
    Route::POST("/save-job",[accountController::class,"saveJob"])->name("account.saveJob");
    Route::GET("/my-job",[accountController::class,"myJob"])->name("account.myJob");
    Route::GET("/edit-job/{jobId}",[accountController::class,"editJob"])->name("account.editJob");
    Route::POST("/update-job/{jobId}",[accountController::class,"updateJob"])->name("account.updateJob");
    Route::POST("/delete-job",[accountController::class,"deleteJob"])->name("account.deleteJob");
    Route::GET("applied-job/",[jobsConroller::class,"appliedJob"])->name("job.appliedJob");
    Route::POST("remove-job/",[jobsConroller::class,"removeJob"])->name("job.removeJob");
    Route::GET("saved-job/",[accountController::class,"savedJob"])->name("job.savedJob");
    Route::POST("remove-saved-job/",[accountController::class,"removeSavedJob"])->name("job.removeSavedJob");
    Route::POST("change-password/",[accountController::class,"changepassword"])->name("job.changepassword");





    });

});




// ---------Flush Cache
// Route::get('/clear', function () {
//     Artisan::call('route:cache');
//     Artisan::call('config:cache');
//     Artisan::call('cache:clear');
//     Artisan::call('view:clear');
//     Artisan::call('optimize');
//     Artisan::call('optimize:clear');
//     return 'Routes, View, Config cache and optimize cleared!';
// });



