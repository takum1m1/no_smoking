<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments'])->orderBy('created_at', 'desc')->paginate(10);;
        return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'max:200'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = auth()->user();


        $post = new Post();
        $post->user_id = $user->id;
        $post->content = $request->content;
        $post->saveOrFail();

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 200);
    }
}
