<?php

use Illuminate\Support\Facades\Routuse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

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
