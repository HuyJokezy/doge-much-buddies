<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['index']]);
        $this->middleware('auth');
    }

    public function requests (Request $request){
        $user = $request->user();
        $list_requests = [];

        $requesters = $user->theFriends()->where('status', '=', 'pending')->get();
        foreach ($requesters as $requester){
            $list_requests[] = $requester;
        }

        return $list_requests;
    }

    // public function targetRequests (Request $request) {
    //     $user = $request->user();
    //     $list_targets = [];

    //     $targets = $user->Friends()->where('status', '=', 'pending')->get();
    //     foreach ($targets as $target){
    //         $list_requests[] = $target;
    //     }

    //     return $list_targets;
    // }

    private function ifFriend (User $user, User $target) {
        if ($user->id == $target->id){
            return True;
        }
        $user_friends = $user->friends()->where('status', '=', 'friend')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return True;
            }
        }

        $user_friends = $user->theFriends()->where('status', '=', 'friend')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return True;
            }
        }

        return False;
    }

    public function addFriend (Request $request, User $target) {
        $user = $request->user();
        $result = array (
            'status' => 'friend',
        );

        if ($this->ifFriend($user, $target)){
            return json_encode($result);
        }

        $friend = new Friend;
        $friend->user_1 = $user->id;
        $friend->user_2 = $target->id;
        $friend->status = 'pending';
        $friend->save();

        $result['status'] = 'pending';
        return json_encode($result);
    }

    public function response (Request $request, User $target) {
        $user = $request->user();
        $result = array (
            'status' => 'accepted',
        );

        $this->validate($request, [
            'response' => 'required|in:accept,deny',
        ]);

        $friend = Friend::where('user_2', '=', $user->id)
                        ->where('user_1', '=', $target->id)
                        ->where('status', '=', 'pending')
                        ->first();
        $friend2 = Friend::where('user_1', '=', $user->id)
                        ->where('user_2', '=', $target->id)
                        ->where('status', '=', 'pending')
                        ->first();
        if ($request->input('response') == 'accept'){
            $friend->status = 'friend';
            $friend->save();
            return json_encode($result);
        } else {
            if ($friend) $friend->delete();
            if ($friend2) $friend2->delete();
            $result['status'] = 'denied';
            return json_encode($result);
        }
    } 
}
