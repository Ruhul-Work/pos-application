<?php

use App\Http\Controllers\backend\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('units')->name('units.')->group(function () {
    // Page
    Route::get('/', [UnitController::class, 'index'])->name('index');
    // DataTables
    Route::post('/list', [UnitController::class, 'listAjax'])->name('list.ajax');
    // AjaxModal forms
    Route::get('/create-modal', [UnitController::class, 'createModal'])->name('createModal');
    Route::get('/{unit}/edit-modal', [UnitController::class, 'editModal'])
        ->whereNumber('unit')->name('editModal');
    // Web form submit (AjaxModal posts) — CSRF applies
    Route::post('/', [UnitController::class, 'store'])->name('store');
    Route::put('/{unit}', [UnitController::class, 'update'])->whereNumber('unit')->name('update');
    Route::delete('/{unit}', [UnitController::class, 'destroy'])->whereNumber('unit')->name('destroy');
});
