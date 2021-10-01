<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Headers: Authorization');
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

//Auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::post('/tokenvalidity', [AuthController::class, 'tokenvalidity'])->name('token.validity');

//Must be guarded routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Auth
    Route::put('/users', [AuthController::class, 'update']);
    Route::delete('/users', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //Contents
    Route::post('/contents', [ContentController::class, 'store']);
    Route::delete('/contents/{slug}', [ContentController::class, 'destroy']);
    Route::put('/contents/{slug}', [ContentController::class, 'update']);
});

//Admin only
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    //Types
    Route::delete('/users/{username}', [AuthController::class, 'destroybyUsername']);
    Route::post('/types', [TypeController::class, 'store']);
    Route::put('/types/{id}', [TypeController::class, 'update']);
    Route::delete('/types/{id}', [TypeController::class, 'destroy']);
});

//Public routes

Route::get('/types', [TypeController::class, 'index']);
Route::get('/types/{username}', [TypeController::class, 'userIndex']);
Route::get('/users', [UserController::class, 'getUsers']);
Route::get('/user/{username}', [UserController::class, 'index'])->name('user.get');
Route::get('/user/{username}/socmed', [UserController::class, 'getSocmed'])->name('socmed.get');
Route::get('/contents/{username}', [ContentController::class, 'index'])->name('content.get.all');
Route::get('/contents/{username}/{type}', [ContentController::class, 'listing'])->name('content.get.bytype');
Route::get('/contents/{username}/{type}/{slug}', [ContentController::class, 'show'])->name('content.get.slug');
