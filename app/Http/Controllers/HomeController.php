<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if ($user->api_token === null){
            $accessToken = $user->createToken('doge')->accessToken;
            // $user = $user->withAccessToken($accessToken);
            $user->api_token = $accessToken;
            $user->save();
        }

        // return view('home');
        //user's wall can get posts of all friend, and post with following dog tagged
        $dogs = $user->follows()->get();
        $posts = [];

        $user_posts = $user->posts()->get();
        foreach ($user_posts as $post) {
            $posts[] = $post;
        }
        foreach ($dogs as $dog) {
            $rows = DB::select('select posts.* 
                                from posts,post_tags   
                                where posts.id=post_tags.post_id 
                                and post_tags.dog_id=' . $dog->id);
            foreach($rows as $row){
                $posts[] = $row;
            }
        }
        foreach ($posts as $index=>$post) {
            //Get owner info
            $postOwner = DB::select('select users.* 
                                from users
                                where users.id=' . $post->owner);
            $posts[$index]->owner = $postOwner[0];
            // Get reactions info                    
            $postReacts = DB::select('select post_reacts.* 
                                from post_reacts   
                                where post_reacts.post_id=' . $post->id);
            $posts[$index]->reactions = $postReacts;
            $posts[$index]->laughCount = 0;
            $posts[$index]->likeCount = 0;
            $posts[$index]->loveCount = 0;
            $posts[$index]->yourReaction = 'None';
            foreach ($postReacts as $react) {
                if ($react->type == 'Laugh') {
                    $posts[$index]->laughCount += 1;
                    if ($react->owner === $user->id) $posts[$index]->yourReaction = 'Laugh';
                }
                if ($react->type == 'Like') {
                    $posts[$index]->likeCount += 1;
                    if ($react->owner === $user->id) $posts[$index]->yourReaction = 'Like';
                }
                if ($react->type == 'Love') {
                    $posts[$index]->loveCount += 1;
                    if ($react->owner === $user->id) $posts[$index]->yourReaction = 'Love';
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
            $posts[$index]->comments = $postComments;
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
            $posts[$index]->tags = $postTags;
        }
        $posts = array_unique($posts, SORT_REGULAR);
        // error_log($posts[0]->reactions[0]->type);
        return view('home', ['posts' => $posts]);
    }
}