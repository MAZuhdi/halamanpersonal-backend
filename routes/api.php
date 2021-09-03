<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization');
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
    Route::put('/user', [AuthController::class, 'update']);
    Route::delete('/user', [AuthController::class, 'destroy']);
    Route::post('/contents', [ContentController::class, 'store']);
    Route::delete('/contents/{slug}', [ContentController::class, 'destroy']);
    Route::put('/contents/{slug}', [ContentController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});

//We Need reserved Username such as user, contents
//Public routes
Route::get('/users', [UserController::class, 'getUsers']);
Route::get('/{username}', [UserController::class, 'index'])->name('user.get');
Route::get('/{username}/socmed', [UserController::class, 'getSocmed'])->name('socmed.get');
Route::get('/{username}/contents', [ContentController::class, 'index'])->name('content.get.all');
Route::get('/{username}/{type}/{slug}', [ContentController::class, 'show'])->name('content.get.slug');
Route::get('/{username}/{type}', [ContentController::class, 'listing'])->name('content.get.bytype');

