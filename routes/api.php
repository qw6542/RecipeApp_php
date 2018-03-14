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

Route::middleware('auth:api')->get('/user',UserController::class.'@getUser');
Route::post('/register',UserController::class.'@create');
Route::get('/users/update',UserController::class.'@update' );
Route::get('/favorite',UserController::class.'@favorite');
Route::get('/kitchen/{id}',RecipeController::class.'@kitchen');

Route::get('/recipes/collections/hot',RecipeController::class.'@hot' );
Route::get('/recipes/{id}',RecipeController::class.'@getRecipeById' );
Route::get('/recipes/collections/new',RecipeController::class.'@new' );
Route::get('/recipes/collections/recent',RecipeController::class.'@recent');
Route::post('/recipes/create',RecipeController::class.'@create' );
Route::post('/recipes/search',RecipeController::class.'@search' );

Route::get('dist/{vue_capture?}', function () {
    return view('vue.index');
})->where('vue_capture', '[\/\w\.-]*');