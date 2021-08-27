<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Public routes
Route::post('/contents', [ContentController::class, 'store' ]);
Route::get('/contents/{username}', [ContentController::class, 'index' ]);
Route::get('/{type}/{username}', [ContentController::class, 'listing' ]);

Route::post('/register', [AuthController::class, 'register' ]);
Route::get('/{username}', [UserController::class, 'index' ]);

Route::get('/socmed/{username}', [UserController::class, 'getSocmed' ]);

//Must be guarded routes
Route::delete('/contents/{slug}', [ContentController::class, 'destroy' ]);
Route::put('/contents/{slug}', [ContentController::class, 'update' ]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
