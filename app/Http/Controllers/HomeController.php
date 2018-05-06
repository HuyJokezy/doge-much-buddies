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
            $postOwner = DB::select('select users.* 
                                from users
                                where users.id=' . $post->owner);
            $postReacts = DB::select('select post_reacts.* 
                                from post_reacts   
                                where post_reacts.post_id=' . $post->id);
            $postComments = DB::select('select post_comments.* 
                                from post_comments   
                                where post_comments.post_id=' . $post->id);
            $posts[$index]->owner = $postOwner[0];
            $posts[$index]->reactions = $postReacts;
            $posts[$index]->comments = $postComments;
            $posts[$index]->laughCount = 0;
            $posts[$index]->likeCount = 0;
            $posts[$index]->loveCount = 0;
            foreach ($postReacts as $react) {
                if ($react->type == 'Laugh') $posts[$index]->laughCount += 1;
                if ($react->type == 'Like') $posts[$index]->likeCount += 1;
                if ($react->type == 'Love') $posts[$index]->loveCount += 1;
            }
        }
        $posts = array_unique($posts, SORT_REGULAR);
        // error_log($posts[0]->reactions[0]->type);
        return view('home', ['posts' => $posts]);
    }
}