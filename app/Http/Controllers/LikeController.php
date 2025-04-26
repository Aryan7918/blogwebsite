<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        abort_if(!Auth::user(), 403);
        try {
            $attributes = $request->all();
            $attributes['user_id'] = Auth::id();
            $attributes['post_id'] = (int)$request->post_id;
            $attributes['is_like'] = (int)$request->is_like;
            //check if like or dislike
            $like = Like::where('user_id', $attributes['user_id'])
                ->where('post_id', $attributes['post_id'])
                ->first();

            //if like or dislike exists then update it
            if ($like) {
                if ($like->is_like != $attributes['is_like']) { //like
                    $like = $like->update($attributes);
                } else {
                    $like = $like->delete();
                }
            } else {
                Like::create($attributes);
            }
            $post = Post::findOrFail($attributes['post_id']);
            $likes = $post->like()->where('is_like', 1)->count();
            $dislikes = $post->like()->where('is_like', 0)->count();
            return response()->json(['likes' => $likes, 'dislikes' => $dislikes]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function liked(Request $request)
    {
        try {

            $attributes = $request->all();
            $attributes['user_id'] = Auth::id();
            $attributes['post_id'] = (int)$request->post_id;
            $like = Like::select('is_like')->where('user_id', $attributes['user_id'])
                ->where('post_id', $attributes['post_id'])
                ->first();
            return response()->json(['is_like' => $like->is_like]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}