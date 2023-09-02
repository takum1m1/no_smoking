<?php

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
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

// ユーザー登録
Route::post('/register', [UserController::class, 'register']);
// ログイン
Route::post('/login', [UserController::class, 'login']);

/**********************************************
 * ログインユーザー用エンドポイント
 **********************************************/
Route::middleware('auth:sanctum')->group(function() {
    // ログアウト
    Route::post('/logout', [UserController::class, 'logout']);
    // アカウント削除
    Route::delete('/delete', [UserController::class, 'destroy']);
    // プロフィール更新
    Route::patch('/profile/update', [UserProfileController::class, 'update']);
    // プロフィール詳細
    Route::get('/profile/{id}', [UserProfileController::class, 'show']);
    // 禁煙情報リセット
    Route::patch('/profile/reset', [UserProfileController::class, 'reset']);
    // 投稿一覧
    Route::get('/posts', [PostController::class, 'index']);
    // 投稿検索
    Route::get('/posts/search', [PostController::class, 'search']);
    // 投稿作成
    Route::post('/posts', [PostController::class, 'store']);
    // 投稿詳細
    Route::get('/posts/{id}', [PostController::class, 'show']);
    // 投稿削除
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    // コメント投稿
    Route::post('/posts/{postId}/comments', [CommentController::class, 'comment']);
    // コメント削除
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);
    // いいね
    Route::post('/posts/{postId}/likes', [LikeController::class, 'like']);
    // いいね解除
    Route::delete('/posts/{postId}/likes', [LikeController::class, 'unlike']);
    // いいね一覧
    Route::get('/likes', [LikeController::class, 'index']);
});

/**********************************************
 * 管理者用エンドポイント
 **********************************************/
Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    // ユーザー一覧
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    // ユーザーBAN
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);
    // 投稿一覧
    Route::get('/admin/posts', [AdminPostController::class, 'index']);
    // 投稿削除
    Route::delete('/admin/posts/{id}', [AdminPostController::class, 'destroy']);
    // コメント削除
    Route::delete('/admin/comments/{commentId}', [AdminCommentController::class, 'destroy']);
});
