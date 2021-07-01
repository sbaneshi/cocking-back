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

Route::get('/recent', function() {
    return \App\Recipe::all()->sortByDesc('created_at');
});

//Route::get('/popular', function() {
//    return \App\Recipe::all()->sortByDesc('created_at');
//});
