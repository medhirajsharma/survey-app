<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyReportController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/public/surveys/{survey}/take', [SurveyResponseController::class, 'create'])->name('public.surveys.take');
Route::post('/public/surveys/{survey}/take', [SurveyResponseController::class, 'store'])->name('public.surveys.store');
Route::get('/public/surveys/{survey}/results/{surveyResponse}', [SurveyResponseController::class, 'showResults'])->name('public.surveys.results');

Route::middleware(['auth'])->group(function () {
    Route::resource('surveys', SurveyController::class);
    Route::resource('surveys.questions', QuestionController::class)->shallow();

    Route::get('/surveys/{survey}/take', [SurveyResponseController::class, 'create'])->name('surveys.take');
    Route::post('/surveys/{survey}/take', [SurveyResponseController::class, 'store']);
    Route::get('/surveys/{survey}/report', [SurveyReportController::class, 'show'])->name('surveys.report');
    Route::get('/surveys/{survey}/report/export', [SurveyReportController::class, 'export'])->name('survey-reports.export');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
