<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ReplyResponse implements Responsable
{
    protected $reply;

    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->reply,
        ], 201);
    }
}