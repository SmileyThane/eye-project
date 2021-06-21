<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/stored-resources', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'index'])->name('get-stored-resources');
    Route::get('/stored-resources/create', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'create'])->name('create-stored-resource');
    Route::post('/stored-resources', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'store'])->name('store-stored-resources');
    Route::get('/stored-resources/{id}', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'find'])->name('find-stored-resource');
    Route::get('/stored-resources/{id}/ssl', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'updateSslInfo'])->name('update-ssl-info');
    Route::patch('/stored-resources/{id}', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'edit'])->name('edit-stored-resource');
    Route::delete('/stored-resources/{id}', [App\Http\Controllers\StoredResource\StoredResourceController::class, 'delete'])->name('delete-stored-resource');

});
