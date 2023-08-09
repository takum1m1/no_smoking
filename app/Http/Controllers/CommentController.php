<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function comment(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'max:200'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = Auth::user();

        $post = Post::findOrFail($postId);

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->content = $request->content;
        $comment->saveOrFail();

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 200);
    }

    public function destroy($postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $userId = Auth::user()->id;

        if ($comment->user_id !== $userId) {
            return response()->json(['message' => 'Unauthorized: You can only delete your own comments.'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
