<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\SurveyReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('surveys', SurveyController::class);
    Route::resource('surveys.questions', QuestionController::class)->shallow();

    Route::get('/surveys/{survey}/take', [SurveyResponseController::class, 'create'])->name('surveys.take');
    Route::post('/surveys/{survey}/take', [SurveyResponseController::class, 'store']);
    Route::get('/surveys/{survey}/report', [SurveyReportController::class, 'show'])->name('surveys.report');
    Route::get('/surveys/{survey}/report/export', [SurveyReportController::class, 'export'])->name('survey-reports.export');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
