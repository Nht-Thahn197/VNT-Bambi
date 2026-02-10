<?php

use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::post('/articles/{article}/likes', [LikeController::class, 'store'])->name('articles.likes.store');
    Route::delete('/articles/{article}/likes', [LikeController::class, 'destroy'])->name('articles.likes.destroy');
    Route::post('/articles/{article}/shares', [ShareController::class, 'store'])->name('articles.shares.store');
    Route::delete('/articles/{article}/shares', [ShareController::class, 'destroy'])->name('articles.shares.destroy');
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('articles.comments.store');
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow.store');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.follow.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tags', TagController::class)->except(['show']);
    Route::patch('comments/{comment}/hide', [AdminCommentController::class, 'hide'])->name('comments.hide');
    Route::patch('comments/{comment}/unhide', [AdminCommentController::class, 'unhide'])->name('comments.unhide');
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});
