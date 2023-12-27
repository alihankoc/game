<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::group(['prefix' => 'games'],function () {
    Route::post('start', [GameController::class, 'startGame']);
    Route::post('end', [GameController::class, 'endGame']);
    Route::get('leaderboard', [GameController::class, 'showLeaderboard']);
});
