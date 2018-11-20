<?php

Route::get('/', 'IndexController@index');

Auth::routes();

Route::get('/compilations/{compilation}', 'Compilations\CompilationController@show')->name('compilation');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', 'UserController@index')->name('settings');
    Route::get('/compilations', 'Compilations\CompilationController@index')->name('compilations');

    Route::post('/tags', 'Compilations\TagController@store')->name('tags_store');
});
