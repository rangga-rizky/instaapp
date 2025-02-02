<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class PostResponse implements Responsable
{
    protected $post, $http_code;

    public function __construct($post, $http_code = 200)
    {
        $this->post = $post;
        $this->http_code = $http_code;
    }

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->post,
        ], $this->http_code);
    }
}