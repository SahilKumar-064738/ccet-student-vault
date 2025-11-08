<?php

use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get subjects by branch and year
Route::get('/subjects', [SubjectController::class, 'getSubjects'])->name('api.subjects');