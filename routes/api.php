<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskListController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Team routes
    Route::apiResource('teams', TeamController::class);
    Route::post('/teams/{team}/invite', [TeamController::class, 'invite']);
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember']);

    // Project routes
    Route::apiResource('projects', ProjectController::class);
    Route::get('/teams/{team}/projects', [ProjectController::class, 'indexByTeam']);

    // List routes
    Route::apiResource('lists', TaskListController::class)->except(['index', 'show']);
    Route::get('/projects/{project}/lists', [TaskListController::class, 'indexByProject']);
    Route::post('/lists/update-positions', [TaskListController::class, 'updatePositions']);

    // Task routes
    Route::apiResource('tasks', TaskController::class);
    Route::get('/lists/{list}/tasks', [TaskController::class, 'indexByList']);
    Route::post('/tasks/update-positions', [TaskController::class, 'updatePositions']);

    // Comment routes
    Route::apiResource('comments', CommentController::class)->except(['index', 'show']);
    Route::get('/tasks/{task}/comments', [CommentController::class, 'indexByTask']);

    // Attachment routes
    Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store']);
    Route::get('/tasks/{task}/attachments', [AttachmentController::class, 'indexByTask']);
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy']);
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download']);
});