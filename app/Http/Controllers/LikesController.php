<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Activity;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require
        //authorization to be able to access
    }
    public function store(Post $post)
    {
        if (auth()->id() == $post->user_id) {
            return auth()
                ->user()
                ->liking()
                ->toggle($post);
        }
        $activity = Activity::where('user_id', auth()->id())
            ->where('target_user_id', $post->user_id)
            ->where('post_id', $post->id)
            ->where('type', 'like')
            ->first();
        if (!empty($activity)) {
            $activity->delete();
        } else {
            Activity::create([
                'user_id' => auth()->id(),
                'target_user_id' => $post->user_id,
                'type' => 'like',
                'message' => "liked your post!",
                'post_id' => $post->id,
            ]);
        }
        return auth()
            ->user()
            ->liking()
            ->toggle($post);
        //returns an array of attach and detach (relationships)
    }
}
