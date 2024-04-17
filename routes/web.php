<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/test', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/files', [App\Http\Controllers\FileController::class, 'index'])->name('files');
Route::get('/downloadFile/{fileId}', [App\Http\Controllers\FileController::class, 'downloadFile'])->name('downloadFile');
Route::get('/addFile', [App\Http\Controllers\FileController::class, 'addFile'])->name('addFile');
Route::post('/saveFile', [App\Http\Controllers\FileController::class, 'saveFile'])->name('saveFile');
Route::post('/checkInFiles', [App\Http\Controllers\FileController::class, 'checkInFiles'])->name('checkInFiles');
Route::post('/checkOutFile', [App\Http\Controllers\FileController::class, 'checkOutFile'])->name('checkOutFile');
Route::get('/filesAuditReport', [App\Http\Controllers\FileController::class, 'filesAuditReport'])->name('filesAuditReport');
Route::post('/getFilesAuditReport', [App\Http\Controllers\FileController::class, 'getFilesAuditReport'])->name('getFilesAuditReport');
Route::post('/updateFile', [App\Http\Controllers\FileController::class, 'updateFile'])->name('updateFile');
