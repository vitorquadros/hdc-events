<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index']);

Route::get('/events/create', [EventController::class, 'create']);

Route::get('/products', function () {
    $search = request('search');
    return view('products', ['search' => $search]);
});

Route::get('/products/{id}', function ($id) {
    return view('product', ['id' => $id]);
});

