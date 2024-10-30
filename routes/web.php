<?php

use Illuminate\Support\Facades\Routuse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentsController;

Route::get('/', [AuthController::class,'index'])->name('home');
Route::get('/register', [AuthController::class,'registerForm'])->name('registerForm');
Route::post('/register', [AuthController::class,'Register'])->name('Register');
Route::get('/login', [AuthController::class,'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class,'Login'])->name('Login');
Route::put('/my/profile/update/{id}', [AuthController::class,'update'])->name('update.profile');
Route::delete('/logout', [AuthController::class,'logout'])->name('logout');
Route::get('/my/profile/edit', [AuthController::class,'editProfile'])->name('edit.profile');
Route::get('/my/profile', [AuthController::class,'profile'])->name('my.profile');
Route::resource( '/posts', PostController::class);
Route::get('/posts', [PostController::class,'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class,'show'])->name('posts.show');
Route::resource('/posts', PostController::class);
Route::post( '/comments/store', [CommentsController::class, 'store'])->name('comments.store');
Route::delete('/comments/destroy/{id}', [CommentsController::class, 'destroy'])->name('comments.destroy');
Route::get('/users/profile/{username}', [PostController::class, 'userProfile'])->name('users.profile');
Route::get('/follow/{id}', [FollowController::class,'follow'])->name('follow');
Route::get('/unfollow/{id}', [FollowController::class,'unfollow'])->name('unfollow');
Route::patch('/read/notify{id}', [NotificationController::class,'readNotify'])->name('mark.notification.read');