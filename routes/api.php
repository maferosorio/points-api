<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/points', 'PointController@store');
Route::delete('/points/{id}', 'PointController@destroy');
Route::put('/points/{id}', 'PointController@update');
Route::get('/points/{id}', 'PointController@show');
Route::get('/points/{id}/{limit}', 'PointController@getNearestPoints')->name('nearest_points');