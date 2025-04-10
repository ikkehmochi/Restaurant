<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TableController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// Group routes for tables with a prefix
Route::group(['prefix' => 'tables'], function () {
    Route::get('/', [TableController::class, 'index'])->name('tables.index');
    Route::get('/create', [TableController::class, 'create'])->name('tables.create');
    Route::post('/', [TableController::class, 'store'])->name('tables.store');
    Route::get('/{table}', [TableController::class, 'show'])->name('tables.show');
    Route::get('/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::put('/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
});
