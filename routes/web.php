<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CollectionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');
Route::get('/anime/{id}/episodes/{episodeId}', [AnimeController::class, 'episode'])->name('anime.episode');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

use App\Http\Controllers\AnimeProgressController;

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{user}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/collections/{user}/favorites', [CollectionController::class, 'getFavorites'])->name('collections.favorites');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/anime/progress', [AnimeProgressController::class, 'saveProgress'])->name('anime.progress');
});
