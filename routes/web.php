<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;

Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/sprints', [SprintController::class, 'store'])->name('sprints.store');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}', [TaskController::class, 'updateUser'])->name('tasks.updateUser');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
