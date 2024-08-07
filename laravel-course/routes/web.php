<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'hello world';
})->name('hello.index');

Route::get('/contact', function () {
    return 'Contact';
});

Route::get('/posts/{id}', function (string $id) {
    return 'Post id ' . $id;
});

Route::get('/recent-post/{day_ago?}', function ($dayAgo = 20) {
    return 'Recent post ' . $dayAgo;
});

Route::get('/products', function () {
    return response([['name' => 'Nguyen Van A']], 200)->header('Content-Type', 'application/json');
});
