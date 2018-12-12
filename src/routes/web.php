<?php

Route::get('/', 'IndexController@index')->name('index');

Auth::routes(['verify' => true]);

Route::get('/compilations/{compilation}', 'Compilations\CompilationController@show')->name('compilation');

Route::middleware(['auth', 'verified'])->group(function () {
//    Route::get('/settings', 'UserController@index')->name('settings');
    Route::get('/about', 'IndexController@about')->name('about');
    Route::post('/tags', 'Compilations\TagController@store')->name('tags_store');
    Route::get('/compilations', 'Compilations\CompilationController@index')->name('compilations');
});
