<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class PostCollectionResponse implements Responsable
{
    protected $posts;
    protected $nextCursor;

    public function __construct($posts, $nextCursor)
    {
        $this->posts = $posts;
        $this->nextCursor = $nextCursor;
    }

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'caption' => $post->caption,
                    'image_url' => $post->image_url,
                    'user' => [
                        'name' => $post->user->name,
                    ],
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                ];
            }),
            'next_cursor' => $this->nextCursor,
        ]);
    }
}