<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DomainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Стандартні маршрути для доменів (index, create, store, show, edit, update, destroy)
    Route::resource('domains', DomainController::class);

    // Додатковий маршрут для ручного запуску перевірки
    Route::post('/domains/{domain}/check', [DomainController::class, 'check'])->name('domains.check');
});

require __DIR__.'/auth.php';
