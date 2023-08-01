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
    Route::put('/profile/update', [UserProfileController::class, 'update']);
    Route::get('/profile/{id}', [UserProfileController::class, 'show']);
    Route::post('/post', [PostController::class, 'post']);
    Route::post('/posts/{postId}/comment', [CommentController::class, 'comment']);
    Route::post('/posts/{postId}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{postId}/unlike', [LikeController::class, 'unlike']);
});
