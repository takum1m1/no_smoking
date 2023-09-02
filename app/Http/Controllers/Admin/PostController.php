<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'like', 'comment'])->orderBy('created_at', 'desc')->paginate(10);;
        return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
