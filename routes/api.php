<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['prefix' => 'menus', 'as' => 'menus.'], function () {
    Route::get('/menu/ingredients', [MenuController::class, 'getMenuIngredients'])->name('ingredients.api');
});
Route::group(['prefix' => 'diningTables', 'as' => '.'], function () {
    Route::get('/all', [TableController::class, 'getAllTables'])->name('tableIndex.api');
    Route::get('/orders', [OrderController::class, 'getOrderDetailsAPI'])->name('tableOrderDetails.api');
});
