<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store']);

Route::patch('/tasks/{id}', [TaskController::class, 'update']);

Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);



