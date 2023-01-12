<?php

use App\Http\Middleware\CheckApiToken;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// TasksLists
Route::middleware('api_token')->get('taskslists', 'App\Http\Controllers\TasksListController@index');
Route::middleware('api_token')->get('taskslists/{taskslist}', 'App\Http\Controllers\TasksListController@show');
Route::middleware('api_token')->post('taskslists', 'App\Http\Controllers\TasksListController@store');
Route::middleware('api_token')->post('taskslists/{taskslist}', 'App\Http\Controllers\TasksListController@update');
Route::middleware('api_token')->delete('taskslists/{taskslist}', 'App\Http\Controllers\TasksListController@delete');

// Tasks
Route::middleware('api_token')->get('tasks', 'App\Http\Controllers\TaskController@index');
Route::middleware('api_token')->get('tasks/{task}', 'App\Http\Controllers\TaskController@show');
Route::middleware('api_token')->post('tasks', 'App\Http\Controllers\TaskController@store');
Route::middleware('api_token')->post('tasks/{task}', 'App\Http\Controllers\TaskController@update');
Route::middleware('api_token')->delete('tasks/{task}', 'App\Http\Controllers\TaskController@delete');

// Users
Route::middleware('api_token')->get('users', 'App\Http\Controllers\UserController@index');
Route::middleware('api_token')->get('users/{user}', 'App\Http\Controllers\UserController@show');
Route::middleware('api_token')->put('users/{user}', 'App\Http\Controllers\UserController@update');
Route::middleware('api_token')->delete('users/{user}', 'App\Http\Controllers\UserController@delete');

// Auth
Route::post('register', 'App\Http\Controllers\UserController@store');
Route::post('login', 'App\Http\Controllers\UserController@login');
