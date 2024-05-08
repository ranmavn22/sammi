<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/export', [\App\Http\Controllers\ExportExcelController::class, 'export'])->name('export');
