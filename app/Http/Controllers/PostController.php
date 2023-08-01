<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required'],
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
