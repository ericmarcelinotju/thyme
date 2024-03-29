<?php

use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Content\BlockController;
use App\Http\Controllers\Content\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Bread\BreadController;
use App\Http\Controllers\Bread\ResourceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('menu', MenuController::class);
    Route::put('/menu/move-up/{id}', [MenuController::class, 'moveUp'])->name('menu.up');
    Route::put('/menu/move-down/{id}', [MenuController::class, 'moveDown'])->name('menu.down');
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('bread', BreadController::class);
    Route::resource('bread.resource', ResourceController::class);

    Route::resource('page', PageController::class);
    Route::resource('block', BlockController::class);
    Route::resource('asset', AssetController::class);
});

require __DIR__ . '/auth.php';
