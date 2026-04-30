<?php

use App\Http\Controllers\GeminiEvaluationController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::options('/gemini/evaluate', fn () => response()->noContent());
Route::post('/gemini/evaluate', GeminiEvaluationController::class);

Route::options('/groq/evaluate', fn () => response()->noContent());
Route::post('/groq/evaluate', [GeminiEvaluationController::class, '__invokeGrok']);

Route::post('/response-simulator', [Controller::class, 'responseSimulator']);
