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
        // $posts = $user->posts()->get();
        // return $posts;
        $dogs = $user->follows()->get();
        $posts = [];
        foreach ($dogs as $dog) {
            $rows = DB::select('select posts.* 
                                from posts,post_tags   
                                where posts.id=post_tags.post_id 
                                and post_tags.dog_id=' . $dog->id);
            foreach($rows as $row){
                $posts[] = $row;
            }
        }
        return $posts;
    }
}