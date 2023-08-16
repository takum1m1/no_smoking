<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::patch('/profile/update', [UserProfileController::class, 'update']);
    Route::get('/profile/{id}', [UserProfileController::class, 'show']);
    Route::patch('/profile/reset', [UserProfileController::class, 'reset']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'comment']);
    Route::delete('/posts/{postId}/comments/{commentId}', [CommentController::class, 'destroy']);
    Route::post('/posts/{postId}/likes', [LikeController::class, 'like']);
    Route::delete('/posts/{postId}/likes', [LikeController::class, 'unlike']);
});
Route::middleware(['auth:sanctum', 'admin'])->group(function() {
});
