<?php

namespace App\Http\Controllers;

use App\PostComment;
use App\PostReact;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostCommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Check if User can comment on post
    private function checkAccess (User $user, Post $post){
        if ($this->checkAuth($user, $post)){
            return True;
        }
        $owner = User::find($post->owner);
        $owner_friends = $owner->friends()->where('status', '=', 'friend')->get();
        foreach ($owner_friends as $friend){
            if ($friend->id == $user->id) {
                return True;
            }
        }

        $owner_friends = $owner->theFriends()->where('status', '=', 'friend')->get();
        foreach ($owner_friends as $friend){
            if ($friend->id == $user->id) {
                return True;
            }
        }

        return False;
    }

    // Check if user is the owner of the post
    private function checkAuth (User $user, Post $post){
        if ($user->id == $post->owner){
            return True;
        }
        return False;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        $user = $request->user();
        $post = Post::find($post_id);

        $result = array(
            'status' => 'ok',
        );
        
        if (!$this->checkAccess($user, $post)){
            $result['status'] = 'unauthorized';
            return json_encode($result);
        }

        $this->validate($request, [
            'comment' => 'required|max:100',
        ]);

        $postComment = new PostComment;
        $postComment->post_id = $post_id;
        $postComment->owner = $user->id;
        $postComment->comment = $request->input('comment');
        $postComment->save();

        return json_encode($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostComment  $postComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post_id, $comment_id)
    {
        $user = $request->user();
        $postComment = PostComment::find($comment_id);

        $result = array(
            'status' => 'ok',
        );

        if ($user->id != $postComment->owner){
            $result['status'] = 'unauthorized';
            return json_encode($result);
        }

        $this->validate($request, [
            'comment' => 'required|max:100',
        ]);

        $postComment = PostComment::find($comment_id);
        $postComment->comment = $request->input('comment');
        $postComment->save();

        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostComment  $postComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $post_id, $comment_id)
    {
        $user = $request->user();
        $postComment = PostComment::find($comment_id);

        $result = array(
            'status' => 'ok',
        );

        if ($user->id != $postComment->owner){
            $result['status'] = 'unauthorized';
            return json_encode($result);
        }

        $postComment = PostComment::find($comment_id);
        $postComment->delete();

        return json_encode($result);
    }
}
