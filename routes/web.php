<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// Public home page
Route::get('/', function () {
    return view('welcome');
});

// Clean redirect for old /dashboard (if anything still uses it)
Route::get('/dashboard', function () {
    return redirect()->route('posts.index');
})->middleware(['auth']);

// Main authenticated routes
Route::middleware(['auth'])->group(function () {
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    //edit and delete post
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Likes & comments
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('posts.comment');
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes
require __DIR__ . '/auth.php';
