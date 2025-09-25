<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyReportController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $query = \App\Models\Survey::latest();

    if ($request->has('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    $query->where(function ($q) {
        $q->where('results_visibility', 'show')
            ->orWhere(function ($q2) {
                $q2->where('results_visibility', 'datetime')
                    ->whereNotNull('results_visible_from')
                    ->where('results_visible_from', '<=', \Carbon\Carbon::now());
            });
    });

    $surveys = $query->paginate(1);
    return view('welcome', compact('surveys'));
});

Route::get('/public/surveys/{survey}/take', [SurveyResponseController::class, 'create'])->name('public.surveys.take');
Route::post('/public/surveys/{survey}/take', [SurveyResponseController::class, 'store'])->name('public.surveys.store');
Route::get('/public/surveys/{survey}/results/{surveyResponse}', [SurveyResponseController::class, 'showResults'])->name('public.surveys.results');
Route::get('/surveys/{survey}/results', [\App\Http\Controllers\SurveyReportController::class, 'publicReport'])->name('surveys.results');

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
