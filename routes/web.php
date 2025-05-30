<?php

use App\Http\Controllers\ProfileController;
use App\Http\Livewire\DrugSearch;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('drug-search');
});

Route::get('/dashboard', function () {
    return redirect()->route('drug-search');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/drug-search', DrugSearch::class)->name('drug-search');

require __DIR__.'/auth.php';
