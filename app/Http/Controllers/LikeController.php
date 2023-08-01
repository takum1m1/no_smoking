<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like($postId)
    {
        $user = auth()->user();

        $post = Post::findOrFail($postId);

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already like it.'], 400);
        }

        $like = new Like();
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        $like->saveOrFail();

        return response()->json(['message' => 'Like created successfully', 'like' => $like], 200);
    }

    public function unlike($postId)
    {
        $user = auth()->user();

        $post = Post::findOrFail($postId);

        $like = $post->likes()->where('user_id', $user->id)->firstOrFail();

        if (!$like) {
            return response()->json(['message' => 'Not like it yet.'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Like deleted successfully'], 200);
    }
}
