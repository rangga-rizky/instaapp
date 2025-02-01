<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Reply;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request, $type, $id)
    {
        $user = auth()->user();
        $model = $this->retrieveModel($type, $id);
        if (!$model) {
            return response()->json(['message' => 'Invalid resource'], 400);
        }
        if ($model->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already liked'], 409);
        }
        $model->likes()->create(['user_id' => $user->id]);
        return response()->json(['message' => 'Liked successfully']);
    }

    public function unlike(Request $request, $type, $id)
    {
        $user = auth()->user();
        $model = $this->retrieveModel($type, $id);
        if (!$model) {
            return response()->json(['message' => 'Invalid resource'], 400);
        }
        $model->likes()->where('user_id', $user->id)->delete();
        return response()->json(['message' => 'Unliked successfully']);
    }

    private function retrieveModel($type, $id)
    {
        switch ($type) {
            case 'post':
            return Post::find($id);
            case 'reply':
            return Reply::find($id);
            default:
            return null;
        }
    }
}
