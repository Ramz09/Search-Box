<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SearchBoxController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search-box', function () {
    return view('SearchBox');
})->name('search-box');

Route::get('/search-box/data', [SearchBoxController::class, 'data'])->name('search-box.data');
Route::post('/search-box/upload', [SearchBoxController::class, 'upload'])->name('search-box.upload');
Route::post('/search-box/check-pdf', [SearchBoxController::class, 'checkPdf'])->name('search-box.check-pdf');
Route::post('/search-box/ocr-pdf', [SearchBoxController::class, 'ocrPdf'])->name('search-box.ocr-pdf');
Route::get('/search-box/ocr-progress/{sessionId}', [SearchBoxController::class, 'ocrProgress'])->name('search-box.ocr-progress');
Route::get('/search-box/options', [SearchBoxController::class, 'options'])->name('search-box.options');
Route::get('/search-box/all-options', [SearchBoxController::class, 'allOptions'])->name('search-box.all-options');
Route::get('/documents/{document}/download', [SearchBoxController::class, 'download'])->name('documents.download');
Route::put('/documents/{document}', [SearchBoxController::class, 'update'])->name('documents.update');
Route::delete('/documents/{document}', [SearchBoxController::class, 'destroy'])->name('documents.destroy');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/options', [AdminDashboardController::class, 'options'])->name('admin.options');
Route::post('/admin/add-type', [AdminDashboardController::class, 'addType'])->name('admin.add-type');
Route::post('/admin/add-status', [AdminDashboardController::class, 'addStatus'])->name('admin.add-status');
Route::post('/admin/add-category', [AdminDashboardController::class, 'addCategory'])->name('admin.add-category');
Route::delete('/admin/delete-type', [AdminDashboardController::class, 'deleteType'])->name('admin.delete-type');
Route::delete('/admin/delete-status', [AdminDashboardController::class, 'deleteStatus'])->name('admin.delete-status');
Route::delete('/admin/delete-category', [AdminDashboardController::class, 'deleteCategory'])->name('admin.delete-category');
