<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::middleware('auth')->group(function() {
    Route::get('user-settings', [App\Http\Controllers\UserSettingsController::class, 'index'])->name('user_settings');
    Route::post('user-settings', [App\Http\Controllers\UserSettingsController::class, 'store'])->name('user_settings.store');
    Route::post('user-url', [App\Http\Controllers\UserSettingsController::class, 'getUserUrl'])->name('user_settings.getUserUrl');
});

Route::middleware(['auth', 'user.settings'])->group(function () {
    Route::match(['get', 'post'], 'dashboard', [App\Http\Controllers\ClientController::class, 'index'])->name('dashboard');

    Route::resources([
        'clients' => App\Http\Controllers\ClientController::class,
        'pets' => App\Http\Controllers\PetController::class,
    ]);

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
