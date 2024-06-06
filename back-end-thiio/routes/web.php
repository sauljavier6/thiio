<?php

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
//cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de prueba userController
    Route::get('/api/user/test', 'App\Http\Controllers\UserController@test');

// Rutas del controlador de usuarios 
    Route::post('/api/register', 'App\Http\Controllers\UserController@register');
    Route::post('/api/login', 'App\Http\Controllers\UserController@login');
    Route::put('/api/user/update/{id}', 'App\Http\Controllers\UserController@update');
    Route::get('/api/user/list', 'App\Http\Controllers\UserController@index')->middleware(ApiAuthMiddleware::class);
    Route::delete('/api/user/delete/{id}', 'App\Http\Controllers\UserController@destroy')->middleware(ApiAuthMiddleware::class);
    Route::get('/api/user/detail/{id}', 'App\Http\Controllers\UserController@detail');