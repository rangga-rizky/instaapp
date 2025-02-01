<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\PostResponse;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $userId = auth()->id();
        $request->validate([
            'caption' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        #best pratice is use storage service that served with CDN
        $imagePath = $request->file('image')->store('posts', 'public');

        $post = new Post();
        $post->caption = $request->caption;
        $post->user_id = $userId;
        $post->image_url = Storage::url($imagePath);
        $post->save();

        return new PostResponse($post);
    }
}