<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

    Route::get('/permission',[PermissionController::class,'index'])->name('permission.list');
    Route::get('/permission/create',[PermissionController::class,'create'])->name('permission.create');
    Route::post('/permission/store',[PermissionController::class,'store'])->name('permission.store');
    Route::get('/permission/edit/{id}',[PermissionController::class,'edit'])->name('permission.edit');
    Route::post('/permission/update',[PermissionController::class,'update'])->name('permission.update');
    Route::delete('/permission/delete/{id}',[PermissionController::class,'destroy'])->name('permission.destroy');

    Route::get('/role',[RoleController::class,'index'])->name('role.list');
    Route::get('/role/create',[RoleController::class,'create'])->name('role.create');
    Route::post('/role/store',[RoleController::class,'store'])->name('role.store');
    Route::get('/role/edit/{id}',[RoleController::class,'edit'])->name('role.edit');
    Route::post('/role/update',[RoleController::class,'update'])->name('role.update');
    Route::delete('/role/delete/{id}',[RoleController::class,'destroy'])->name('role.destroy');


    Route::get('/article',[ArticleController::class,'index'])->name('article.list');
    Route::get('/article/create',[ArticleController::class,'create'])->name('article.create');
    Route::post('/article/store',[ArticleController::class,'store'])->name('article.store');
    Route::get('/article/edit/{id}',[ArticleController::class,'edit'])->name('article.edit');
    Route::post('/article/update',[ArticleController::class,'update'])->name('article.update');
    Route::delete('/article/delete/{id}',[ArticleController::class,'destroy'])->name('article.destroy');

    Route::get('/user',[UserController::class,'index'])->name('user.list');
    Route::get('/user/create',[UserController::class,'create'])->name('user.create');
    Route::post('/user/store',[UserController::class,'store'])->name('user.store');
    Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('user.edit');
    Route::post('/user/update',[UserController::class,'update'])->name('user.update');
    Route::delete('/user/delete/{id}',[UserController::class,'destroy'])->name('user.destroy');

});

require __DIR__.'/auth.php';
