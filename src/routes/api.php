<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('tags/{name?}', 'Compilations\TagController@index')->name('api_tags_search');
