<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function comment(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = auth()->user();

        $post = Post::findOrFail($postId);

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->content = $request->content;
        $comment->saveOrFail();

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 200);
    }
}
