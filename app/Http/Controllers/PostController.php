<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostTag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $this->validate($request, [
            'content' => 'required|max:100',
            'postimg' => 'image|nullable|max:1999',
        ]);
        
        $statement = DB::select("SHOW TABLE STATUS LIKE 'posts'");
        $id = $statement[0]->Auto_increment;

        // Handle file upload
        if ($request->hasFile('postimg')){
           // Get filename with extension
           $filenameWithExt = $request->file('postimg')->getClientOriginalName();
           // Get just filename
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           // Get just extension
           $extension = $request->file('postimg')->getClientOriginalExtension();
           // Filename to store
           $fileNameToStore = 'post_' . $id . '_user_' . $user->id . '.' . $extension;
           // Upload image
           $path = $request->file('postimg')->storeAs('public/posts/', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $post = new Post;
        $post->content = $request->input('content');
        $post->image = 'posts/' . $fileNameToStore;
        $post->owner = $user->id;
        
        $post->save();
        
        if ($request->input('tags')){
            foreach ($request->input('tags') as $dog_id) {
                $postTag = new PostTag;
                $postTag->post_id = $id;
                $postTag->dog_id = $dog_id;
                $postTag->save();
            }
        }

        return redirect('/post/' . $id);
    }

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

    private function checkAuth (User $user, Post $post){
        if ($user->id == $post->owner){
            return True;
        }
        return False;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // $user = Auth::user();
        // if (!$this->checkAccess($user, $post)){
        //     abort(403, "Unauthorized access.");
        // }

        // Everyone can see the post if have link
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $user = Auth::user();
        if (!$this->checkAuth($user, $post)){
            abort(403, "Unauthorized access.");
        }
        return view('post.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$this->checkAuth($user, $post)){
            abort(403, "Unauthorized access.");
        }

        $this->validate($request, [
            'content' => 'required|nullable|max:100',
            'postimg' => 'image|nullable|max:1999',
        ]);

        // Handle file upload
        if ($request->hasFile('postimg')){
           // Get filename with extension
           $filenameWithExt = $request->file('postimg')->getClientOriginalName();
           // Get just filename
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           // Get just extension
           $extension = $request->file('postimg')->getClientOriginalExtension();
           // Filename to store
           $fileNameToStore = 'post_' . $id . '_user_' . $user->id . '.' . $extension;
           // Upload image
           $path = $request->file('postimg')->storeAs('public/posts/', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $post = Post::find($post);
        $post->content = null !== $request->input('content') ? $request->input('content') : $post->content;
        if ($request->hasFile('postimg')){
            $post->image = 'posts/' . $fileNameToStore;
        }

        $post->save();
        return redirect('/post/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = Auth::user();
        if (!$this->$this->checkAuth($user, $post)){
            abort(403, "Unauthorized access.");
        }
        $post = Post::find($post);

        if ($post->image != 'posts/noimage.jpg'){
            Storage::delete('public/' . $post->image);
        }
        $dog->delete();
        return redirect('/home/')->with('success', 'Successfully deleted.');
    }
}
