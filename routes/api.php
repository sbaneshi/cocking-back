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
    return DB::select(DB::raw('select * from recipes order by created_at desc'));
});

Route::get('/popular', function () {
    return DB::select(DB::raw('select recipes.id, name, photos, ingredients, steps, count(*) as like_count from recipes join likes on recipes.id = likes.recipe_id group by id order by like_count desc'));
});

Route::get('/recipe/{id}', function (Request $request) {
    return Recipe::query()->select('*')->where('id', '=', $request->id)->get();
});

Route::get('/recipe/author/{id}', function (Request $request) {
    return DB::select(DB::raw("select users.name as name from users join recipes on users.id=recipes.user_id where recipes.id = {$request->id}"));
});

Route::post('/register', 'Auth\RegisterController@register');

Route::post('/login', 'Auth\LoginController@login');

Route::middleware("auth:api")->post('/new_recipe', function (Request $request) {
    return Recipe::create([
        'user_id' => Auth::guard('api')->id(),
        'name' => $request->name,
        'photos' => '12',
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
    return Like::query()->select("*")->when('user_id', '=', Auth::guard('api')->id())->where('recipe_id', '=', $request->recipe_id)->get();
});

Route::get('/likes/{recipe_id}', function (Request $request) {
    return DB::select(DB::raw("select count(*) as like_count from recipes join likes on recipes.id = likes.recipe_id where recipes.id = {$request->recipe_id} group by id"));
});
