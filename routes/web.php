<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UploadController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::post('/upload', [UploadController::class, 'index'])->name('upload');
