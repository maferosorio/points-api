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


Route::post('/points', 'PointController@store')->name('points.store');
Route::delete('/points/{id}', 'PointController@destroy')->name('points.destroy');
Route::put('/points/{id}', 'PointController@update')->name('points.update');
Route::get('/points/{id}', 'PointController@show')->name('points.show');
Route::get('/nearest-points/{id}/{limit?}', 'PointController@getNearestPoints')->name('points.nearest_points');