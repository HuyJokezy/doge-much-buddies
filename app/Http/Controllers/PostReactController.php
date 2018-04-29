<?php

namespace App\Http\Controllers;

use App\PostReact;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostReactController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        // Defined by user and the post on which he/she reacts
        $user = $request->user();
        $this->validate($request, [
            'type' => 'required|in:Like,Love,Laugh',
        ]);

        $postReact = new PostReact;
        $postReact->owner = $user->id;
        $postReact->post_id = $post_id;
        $postReact->type = $request->input('type');

        $postReact->save();
        $count = \App\PostReact::where('post_id', '=', $post_id)->count();
        $result = array (
            'status' => 'ok',
            'total' => $count,
        );
        return json_encode($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostReact  $postReact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post_id)
    {
        $user = $request->user();
        $this->validate($request, [
            'type' => 'required|in:Like,Love,Laugh',
        ]);
        
        $postReact = PostReact::where('post_id', '=', $post_id)->where('owner', '=', $user->id)->first();
        $postReact->type = $request->input('type');
        $postReact->save(); 
        $result = array (
            'status' => 'ok',
        );
        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostReact  $postReact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $post_id)
    {
        $user = $request->user();
        
        $postReact = PostReact::where('post_id', '=', $post_id)->where('owner', '=', $user->id)->first();
        $postReact->delete();
        
        $result = array (
            'status' => 'ok',
        );
        return json_encode($result);
    }
}
