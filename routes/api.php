<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\ImageController;
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

// Route::get("/hi",function(){
//     return response()->json(['data'=>'hi']);

// });
Route::post("/register",[ApiController::class,'register']);
Route::post("/login",[LoginController::class,'login']);
Route::post('/forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [NewPasswordController::class, 'reset']);


Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get("/user",[ApiController::class,'getUser']);
    Route::post("/addPatient",[PatientController::class,'addPatient']);
    Route::post("/getPatient",[PatientController::class,'getPatients']);
    Route::post("/getPatientRecords",[PatientController::class,'getPatientRecords']);
    Route::post("/addPatientRecord",[PatientController::class,'addPatientRecord']);
    Route::put("/updatePatient/{id}",[PatientController::class,'updatePatient']);
    Route::post('/change-password', [LoginController::class,'change_password']);
    Route::put("/updateProfile/{id}",[LoginController::class,'updateProfile']);
    Route::post("/updateImage",[ImageController::class,'addimage']);
    Route::get("/getImage/{id}",[ImageController::class,'getImage']);
});


// Route::post("/addPatient",[PatientController::class,'addPatient']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
