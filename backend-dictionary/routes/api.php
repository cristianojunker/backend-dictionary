<?php

use App\Http\Controllers\API\DictionaryAPIController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
    
    Route::get('/entries/en/{word}', [DictionaryAPIController::class, 'entries'])->middleware('auth:api')->name('entries');

    Route::get('/user/me/history', [DictionaryAPIController::class, 'listHistory'])->middleware('auth:api')->name('list.history');
    Route::get('/user/me/favorites', [DictionaryAPIController::class, 'listFavoriteWords'])->middleware('auth:api')->name('list.favorites');
    Route::get('/entries/en', [DictionaryAPIController::class, 'listWords'])->middleware('auth:api')->name('list.words');
    Route::get('/user/me/save-words', [DictionaryAPIController::class, 'loadAndSaveWords'])->middleware('auth:api')->name('load.words');
    Route::post('/entries/en/{word}/favorite', [DictionaryAPIController::class, 'favorite'])->middleware('auth:api')->name('favorite');
    Route::delete('/entries/en/{word}/unfavorite', [DictionaryAPIController::class, 'unfavorite'])->middleware('auth:api')->name('unfavorite');
});