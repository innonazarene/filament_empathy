<?php

use App\Http\Controllers\FailedJobController;
use App\Http\Controllers\PasswordResetTokenController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//for auth:sanctum you can add routes here
Route::prefix("YOUR_PREFIX")->middleware('auth:sanctum')->group(function(){});

Route::prefix("public")->group(function(){
    Route::Resource('failedjobs', FailedJobController::class)->only(['index','show']);
    Route::Resource('passwordresettokens', PasswordResetTokenController::class)->only(['index','show']);
    Route::Resource('personalaccesstokens', PersonalAccessTokenController::class)->only(['index','show']);
    Route::Resource('users', UserController::class)->only(['index','show']);
});
