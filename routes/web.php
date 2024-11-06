<?php

use Illuminate\Support\Facades\Routuse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\NotificationController;

Route::get('/', [AuthController::class,'index'])->name('home');
Route::get('/register', [AuthController::class,'registerForm'])->name('registerForm');
Route::post('/register', [AuthController::class,'Register'])->name('Register');
Route::get('/login', [AuthController::class,'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class,'Login'])->name('Login');
Route::put('/my/profile/update', [AuthController::class,'update'])->name('update.profile');
Route::delete('/logout', [AuthController::class,'logout'])->name('logout');
Route::get('/my/profile/edit', [AuthController::class,'editProfile'])->name('edit.profile')->middleware('checkAuth');
Route::get('/my/profile', [AuthController::class,'profile'])->name('my.profile')->middleware('checkAuth');
Route::resource( '/posts', PostController::class);
Route::get('/posts', [PostController::class,'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class,'show'])->name('posts.show');
Route::resource('/posts', PostController::class);
Route::post( '/comments/store', [CommentsController::class, 'store'])->name('comments.store')->middleware('checkAuth');
Route::delete('/comments/destroy/{id}', [CommentsController::class, 'destroy'])->name('comments.destroy')->middleware('checkAuth');
Route::get('/users/profile/{username}', [PostController::class, 'userProfile'])->name('users.profile');
Route::get('/follow/{id}', [FollowController::class,'follow'])->name('follow')->middleware('checkAuth');
Route::get('/unfollow/{id}', [FollowController::class,'unfollow'])->name('unfollow')->middleware('checkAuth');
Route::patch('/read/notify{id}', [NotificationController::class,'readNotify'])->name('mark.notification.read');
Route::get('/notify/{username}', [NotificationController::class,'unReadNotification'])->name('follow.notify')->middleware('checkAuth');;