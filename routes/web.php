<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashbaordController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SocietyFlatTypeController;
use App\Http\Controllers\Admin\SocietyMenuController;
use App\Http\Controllers\Admin\SocietyUserController;
use App\Http\Controllers\Admin\SocietyUserTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserTypeController;
use App\Http\Controllers\Admin\UserTypePermissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Society\BuildingController;
use App\Http\Controllers\Society\DashbaordSocietyController;
use App\Http\Controllers\Society\FlatController;
use App\Http\Controllers\Society\MetterController;
use App\Http\Controllers\Society\SiteUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'login']);

// Route::get('/dashboard', [UserController::class, 'dashboard']);
// Route::get('/users', [UserController::class, 'users']);

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::prefix('admin')
    ->middleware(['auth.admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashbaordController::class, 'dashboard'])->name('dashboard');
        // Route::get('/users', [UserController::class, 'index'])->name('users');
        // Route::post('/users', [UserController::class, 'store'])->name('users.store');

        Route::resource('menus', MenuController::class);
        Route::resource('user-types', UserTypeController::class);
        Route::resource('users', UserController::class);
        Route::resource('user-types-permissions', UserTypePermissionController::class);
        Route::resource('society-user-types', SocietyUserTypeController::class);
        Route::post('user-types-permissions/get-permissions', [UserTypePermissionController::class, 'getPermissions'])->name('permissions.get');
        Route::resource('society-user', SocietyUserController::class);
        Route::resource('society-flat-type', SocietyFlatTypeController::class);
        Route::resource('society-menus', SocietyMenuController::class);


    });

Route::get('/society/login', [AuthController::class, 'login'])->name('society.login');
Route::post('/society/login', [AuthController::class, 'authenticate'])->name('society.authenticate');
Route::prefix('society')
    ->middleware(['auth.society'])
    ->name('society.')
    ->group(function () {
        Route::get('dashboard', [DashbaordSocietyController::class, 'dashboard'])->name('dashboard');
        Route::resource('society-user', SiteUserController::class);
        Route::resource('building', BuildingController::class);
        Route::resource('flat', FlatController::class);
        Route::resource('meter', MetterController::class);

    });
