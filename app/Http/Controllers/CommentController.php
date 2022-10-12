<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class CommentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Post $post)
    {
        $all_comments = User::join(
            'comments',
            'users.id',
            '=',
            'comments.user_id'
        )
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('comments.post_id', $post->id)
            ->get(['users.username', 'comments.text', 'profiles.image']);
        return $all_comments;
    }
    public function store(Post $post)
    {
        $data = request()->validate([
            'comment' => 'required|string|max:255',
        ]);
        $post->comments()->create([
            'text' => $data['comment'],
            'user_id' => auth()->user()->id,
        ]);
        
        return auth()
            ->user()
            ->profile->image;
    }
}
