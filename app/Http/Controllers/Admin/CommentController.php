<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
