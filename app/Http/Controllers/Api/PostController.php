<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\PostResponse;
use App\Http\Responses\PostCollectionResponse;
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
        $post->image_url = request()->getSchemeAndHttpHost() . Storage::url($imagePath);
        $post->save();

        return new PostResponse($post, 201);
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $limit = $request->input('limit', 10);
        $cursor = $request->input('cursor');

        $query = Post::with(['user'])
            ->withCount(['likes', 'replies'])
            ->orderBy('id', 'desc');

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $posts = $query->limit($limit + 1)->get();

        $nextCursor = null;
        if ($posts->count() > $limit) {
            $nextCursor = $posts->last()->id;
            $posts = $posts->slice(0, $limit);
        }

        $posts->each(function ($post) use ($userId) {
            $post->is_liked_by_user = $post->likes()->where('user_id', $userId)->exists();
        });

        return new PostCollectionResponse($posts, $nextCursor);
    }

    public function show(Request $request)
    {
        $userId = auth()->id();
        $postId = $request->route('id');

        $post = Post::with(['user'])
            ->withCount(['likes', 'replies'])
            ->find($postId);
        
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }
        $post->replies = $post->replies()
        ->select('id', 'post_id', 'user_id', 'message', 'created_at', 'updated_at')
        ->with(['user:id,name'])
        ->get();
        return new PostResponse($post);
    }
}