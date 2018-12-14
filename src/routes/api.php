<?php

Route::get('/compilations/exists/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    return json_encode([
        'exists' => $user->compilations()->exists(),
    ]);
})->name('compilations-exists');
