<?php

namespace App\Http\Controllers;

use App\PostReact;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        // Defined by user and the post on which he/she reacts
        $user = $request->user();
        $this->validate($request, [
            'post' => 'required|integer',
            'type' => 'required|in:Like,Love,Laugh',
        ]);
        $postReact = new PostReact;
        $postReact->owner = $user->id;
        $postReact->post_id = $request->input('post');
        $postReact->type = $request->input('type');

        $postReact->save();
        // return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostReact  $postReact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostReact $postReact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostReact  $postReact
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostReact $postReact)
    {
        //
    }
}
