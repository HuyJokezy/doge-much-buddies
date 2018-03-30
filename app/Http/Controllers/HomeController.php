<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        return view('home');
    }
}