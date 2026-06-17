<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;


Route::redirect('/', '/admin');
Route::redirect('/dashboard', '/admin');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');