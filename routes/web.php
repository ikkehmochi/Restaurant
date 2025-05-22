<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

// Group routes for tables with a prefix
Route::group(['prefix' => 'tables', 'as' => 'tables.'], function () {
    Route::get('/', [TableController::class, 'index'])->name('index');
    Route::get('/create', [TableController::class, 'create'])->name('create');
    Route::post('/', [TableController::class, 'store'])->name('store');
    Route::get('/{table}', [TableController::class, 'show'])->name('show');
    Route::get('/{table}/edit', [TableController::class, 'edit'])->name('edit');
    Route::put('/{table}', [TableController::class, 'update'])->name('update');
    Route::delete('/{table}', [TableController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'menus', 'as' => 'menus.'], function () {
    Route::get('/', [MenuController::class, 'index'])->name('index');
    Route::get('/category/{category_id}', [MenuController::class, 'indexByCat'])->name('indexByCat');
    Route::get('/menu/ingredients', [MenuController::class, 'getMenuIngredients'])->name('ingredients.api');
    Route::get('/create', [MenuController::class, 'create'])->name('create');
    Route::post('/', [MenuController::class, 'store'])->name('store');
    Route::get('/{menu}', [MenuController::class, 'show'])->name('show');
    Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
    Route::patch('/{menu}', [MenuController::class, 'update'])->name('update');
    Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
});
Route::group(['prefix' => 'menuCategories'], function () {
    Route::get('/', [MenuCategoryController::class, 'index'])->name('menuCategories.index');
    Route::get('/create', [MenuCategoryController::class, 'create'])->name('menuCategories.create');
    Route::post('/', [MenuCategoryController::class, 'store'])->name('menuCategories.store');
    Route::get('/{menuCategory}/edit', [MenuCategoryController::class, 'edit'])->name('menuCategories.edit');
    Route::patch('/{menuCategory}', [MenuCategoryController::class, 'update'])->name('menuCategories.update');
    Route::delete('/{menuCategory}', [MenuCategoryController::class, 'destroy'])->name('menuCategories.destroy');
});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::get('/{order}/invoice/pdf', [OrderController::class, 'printPDF'])->name('orders.printPDF');
    Route::patch('/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

Route::group(['prefix' => 'ingredients'], function () {
    Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::get('/create', [IngredientController::class, 'create'])->name('ingredients.create');
    Route::post('/', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
    Route::get('/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
    Route::get('/{ingredient}/stock/edit', [IngredientController::class, 'stokEdit'])->name('ingredients.stockEdit');
    Route::patch('/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
});

Route::group(['prefix' => 'homepage', 'as' => 'homepage.'], routes: function () {
    Route::get('/diningtables', [TableController::class, 'homePageTableIndex'])->name('tableIndex');
    Route::get('/diningtables/all', [TableController::class, 'getAllTables'])->name('tableIndex.api');
    Route::get('/diningtables/orders', [OrderController::class, 'getOrderDetailsAPI'])->name('tableOrderDetails.api');
});
