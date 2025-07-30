<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AttachmentController;

Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/sprints', [SprintController::class, 'store'])->name('sprints.store');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::resource('projects', ProjectController::class)->except(['index', 'show']);
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('/tasks/{id}/delete', [TaskController::class, 'delete'])->name('tasks.delete');
Route::put('/tasks/{id}/update-user', [TaskController::class, 'updateUser'])->name('tasks.updateUser');
Route::put('/tasks/{id}/update-status', [TaskController::class, 'updateStatus']);
Route::put('/sprints/{id}/toggle-status', [SprintController::class, 'toggleStatus']);
Route::post('/sprints/{id}/delete', [SprintController::class, 'destroy'])->name('sprints.destroy');
Route::post('/sprints/{id}/update', [SprintController::class, 'update'])->name('sprints.update');
Route::put('/tasks/{id}/type', [TaskController::class, 'updateType'])->name('tasks.updateType');
Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store'])->name('tasks.attachments.store');

Route::get('/', function () {
    return view('welcome');
});
