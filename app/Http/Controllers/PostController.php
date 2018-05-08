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
        $user = \Auth::user();

        $followedDogs = DB::select('select dogs.* 
                                from dogs
                                where dogs.owner=' . $user->id);
        return view('post.create', [
            'followedDogs'=>$followedDogs
        ]);
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
            $fileNameToStore = '';
        }

        $post = new Post;
        $post->content = $request->input('content');
        if ($request->hasFile('postimg')) {
            $post->image = 'posts/' . $fileNameToStore;
        }
        $post->owner = $user->id;
        
        $post->save();
        
        if ($request->input('tags')){
            $tags = json_decode($request->input('tags'));
            foreach ($tags as $dog_id) {
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
            $user = Auth::user();
            // if (!$this->checkAuth($user, $post)){
            //     
            // }
            $post->isOwner = $this->checkAuth($user, $post);
            $post->canAccess = $this->checkAccess($user, $post);
            //Get owner info
            $postOwner = DB::select('select users.* 
                                from users
                                where users.id=' . $post->owner);
            $post->owner = $postOwner[0];
            // Get reactions info                    
            $postReacts = DB::select('select post_reacts.* 
                                from post_reacts   
                                where post_reacts.post_id=' . $post->id);
            $post->reactions = $postReacts;
            $post->laughCount = 0;
            $post->likeCount = 0;
            $post->loveCount = 0;
            $post->yourReaction = 'None';
            foreach ($postReacts as $react) {
                if ($react->type == 'Laugh') {
                    $post->laughCount += 1;
                    if ($react->owner === $user->id) $post->yourReaction = 'Laugh';
                }
                if ($react->type == 'Like') {
                    $post->likeCount += 1;
                    if ($react->owner === $user->id) $post->yourReaction = 'Like';
                }
                if ($react->type == 'Love') {
                    $post->loveCount += 1;
                    if ($react->owner === $user->id) $post->yourReaction = 'Love';
                }
            }
            // Get comments info
            $postComments = DB::select('select post_comments.* 
                                from post_comments   
                                where post_comments.post_id=' . $post->id);
            foreach ($postComments as $indexComment => $comment) {
                $ownersOfComment = DB::select('select users.* 
                                from users
                                where users.id=' . $comment->owner);
                $postComments[$indexComment]->ownerObject = $ownersOfComment[0];          
            }
            $post->comments = $postComments;
            // Get tags info
            $postTags = DB::select('select post_tags.* 
                                from post_tags   
                                where post_tags.post_id=' . $post->id);
            foreach ($postTags as $indexTag => $tag) {
                $dogsTagged = DB::select('select dogs.* 
                                from dogs
                                where dogs.id=' . $tag->dog_id);
                $postTags[$indexTag]->dog = $dogsTagged[0];          
            }
            $post->tags = $postTags;

            // Everyone can see the post if have link
            return view('post.view', [
                'post'=>$post
            ]);
            // return $post;
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
        $followedDogs = DB::select('select dogs.* 
                                from dogs
                                where dogs.owner=' . $user->id);
        return view('post.edit', [
            'post'=>$post,
            'followedDogs'=>$followedDogs
        ]);
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
            'content' => 'nullable|max:100',
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
           $fileNameToStore = 'post_' . $post->id . '_user_' . $user->id . '.' . $extension;
           // Upload image
           $path = $request->file('postimg')->storeAs('public/posts/', $fileNameToStore);
        } else {
            $fileNameToStore = '';
        }

        // $post = Post::find($post);
        $post->content = null !== $request->input('content') ? $request->input('content') : $post->content;
        if ($request->hasFile('postimg')){
            $post->image = 'posts/' . $fileNameToStore;
        }

        $post->save();
        $result = array (
            'status' => 'success',
        );
        return $result;
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
        if (!$this->checkAuth($user, $post)){
            abort(403, "Unauthorized access.");
        }

        // $post = Post::find($post->id);
        
        if ($post->image != ''){
            Storage::delete('public/' . $post->image);
        }
        $post->delete();
        // return redirect('/home/')->with('success', 'Successfully deleted.');
        $result = array (
            'status' => 'success',
        );
        return $result;
    }
}
