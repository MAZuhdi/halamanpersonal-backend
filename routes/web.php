<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
});

//Types
Route::get('/types', [TypeController::class, 'indexweb'])->name('type.index');
Route::get('/types/create', [TypeController::class, 'create']);
Route::get('/types/{id}/edit', [TypeController::class, 'edit']);

Route::post('/types', [TypeController::class, 'store'])->name('type.create');
Route::put('/types/{id}', [TypeController::class, 'updateweb'])->name('type.update');
Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('type.delete');
