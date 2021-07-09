<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

JsonApi::register('default')->routes(function ($api) {
    $api->resource('slugs');
    $api->resource('images');

    $api->resource('books')->relationships(function ($relations) {
        $relations->hasMany('slugs');
        $relations->hasMany('images');
        $relations->hasMany('genres');
        $relations->hasMany('authors');
        $relations->hasOne('publisher');
    });

    $api->resource('genres')->relationships(function ($relations) {
        $relations->hasMany('slugs');
    });

    $api->resource('authors')->relationships(function ($relations) {
        $relations->hasMany('books');
        $relations->hasMany('slugs');
        $relations->hasMany('images');
    });

    $api->resource('publishers');
});
