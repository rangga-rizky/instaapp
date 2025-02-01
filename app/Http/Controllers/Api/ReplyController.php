<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Http\Responses\ReplyResponse;


class ReplyController extends Controller
{
    public function create(Request $request)
    {
        $userId = auth()->id();
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'message' => 'required|string|max:1000',
        ]);

        $reply = new Reply();
        $reply->post_id = $request->post_id;
        $reply->user_id = $userId;
        $reply->message = $request->message;
        $reply->save();

        return new ReplyResponse($reply);
    }
}
