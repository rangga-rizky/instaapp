<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class PostResponse implements Responsable
{
    protected $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->post,
        ], 201);
    }
}