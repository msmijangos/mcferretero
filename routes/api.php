<?php

use App\Http\Controllers\apiController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login/{usuario}/{contrasena}', [apiController::class, 'login'])->name('login');

Route::post('/login', [apiController::class, 'login'])->name('login');

Route::put('/editUsuario', [apiController::class, 'editUsuario'])->name('editUsuario');
Route::post('/agregarUsuario', [apiController::class, 'agregarUsuario'])->name('agregarUsuario');
Route::post('/eliminarUsuario', [apiController::class, 'eliminarUsuario'])->name('eliminarUsuario');

Route::put('/editAlmacen', [apiController::class, 'editAlmacen'])->name('editAlmacen');
