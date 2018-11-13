<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', 'UserController@index')->name('settings');
    Route::get('/compilations', 'Compilations\CompilationController@index')->name('compilations');
    Route::get('/compilations/{compilation}', 'Compilations\CompilationController@show')->name('compilation');

    Route::post('/tags', 'Compilations\TagController@store')->name('tags_store');
});
