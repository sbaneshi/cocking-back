<?php

use App\Like;
use App\Recipe;
use App\User;
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

Route::get('/recent', function () {
    return Recipe::all()->sortByDesc('created_at');
});

Route::get('/popular', function () {
    return DB::select(DB::raw('select recipes.id, name, photos, ingredients, steps, count(*) as like_count from recipes join likes on recipes.id = likes.recipe_id group by id order by like_count desc'));
});

Route::post('/register', 'Auth\RegisterController@register');

Route::post('login', 'Auth\LoginController@login');

Route::middleware("auth:api")->post('/new_recipe', function (Request $request) {
    return Recipe::create([
        'user_id' => Auth::guard('api')->id(),
        'name' => $request->name,
        'photos' => $request->photos,
        'ingredients' => $request->ingredients,
        'steps' => $request->steps
    ]);
});

Route::middleware("auth:api")->post('/like', function (Request $request) {
    return Like::create([
        'user_id' => Auth::guard('api')->id(),
        'recipe_id' => $request->recipe_id
    ]);
});

Route::middleware("auth:api")->get('/is_liked/{recipe_id}', function (Request $request) {
    return Like::query()->select(allOf())->when('user_id', '=', Auth::guard('api')->id())->where('recipe_id', '=', $request->recipe_id)->get();
});