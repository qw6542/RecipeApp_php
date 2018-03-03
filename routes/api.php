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


Route::get('/admin','UserController@admin' );
Route::post('/admin','UserController@admin' );

Route::get('/users','UserController@users' );

Route::get('/users/{id}','UserController@getUser' );


Route::post('/users/create',UserController::class.'@create' );

Route::get('/users/update',UserController::class.'@update' );

Route::get('/recipes/hot',RecipeController::class.'@hot' );

Route::get('/recipes/{id}/get',RecipeController::class.'@getRecipeByid' );
Route::get('/recipes/new',RecipeController::class.'@new' );
Route::get('/recipes/recent',RecipeController::class.'@recent' );
Route::post('/recipes/create',RecipeController::class.'@create' );