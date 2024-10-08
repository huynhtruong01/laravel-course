<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyAccount;
use Illuminate\Support\Facades\Route;

$posts = [
    [
        'id' => 1,
        'title' => 'Post 1'
    ],
    [
        'id' => 2,
        'title' => 'Post 2'
    ],
    [
        'id' => 3,
        'title' => 'Post 3'
    ],
    [
        'id' => 4,
        'title' => 'Post 4'
    ],
    [
        'id' => 5,
        'title' => 'Post 5'
    ]
];

Route::get('/', function () {
    return redirect()->route('posts', ['id' => 2]);
});

Route::get('/hello', function () {
    return 'hello world';
})->name('hello.index');

Route::get('/contact', function () {
    return 'Contact';
});

Route::prefix('/posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show'])->middleware('verify-account');
    Route::post('/', [PostController::class, 'create']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'delete']);
});

Route::get('/recent-post/{day_ago?}', function ($dayAgo = 20) {
    return 'Recent post ' . $dayAgo;
});

Route::get('/products', function () {
    return response([['name' => 'Nguyen Van A']], 200)->header('Content-Type', 'application/json');
});

Route::get('/home', function () {
    return 'Home page';
})->name('home-page');

Route::get('/download', function () {
    return response()->download(public_path('/image-1.jpg'), 'Natural');
});

Route::resource('users', UserController::class)->only(['index']);
