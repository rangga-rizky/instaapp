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
            'data' => [
                'id' => $this->reply->id,
                'message' => $this->reply->message,
                'user' => [
                    'name' => $this->reply->user->name,
                ],
                'created_at' => $this->reply->created_at,
            ]
        ], 201);
    }
}