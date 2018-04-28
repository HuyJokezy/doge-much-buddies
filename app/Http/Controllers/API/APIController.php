<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    // Get all information of the current logged in user (except password, tokens)
    public function getUser(Request $request) {
        return $request->user();
    }

    // Get list of dogs of the current logged in user
    public function getOwnerDogs(Request $request) {
        $user = $request->user();
        return $user->dogs()->get();
    }

    // Get list of dogs which the current logged in user follows
    public function getUserFollowDogs(Request $request) {
        $user = $request->user();
        return $user->follows()->get();
    }
    
    // Get list of reaction of the post with post_id = {id}
    public function getPostReactions($post_id) {
        $post = \App\Post::find($post_id);
        return $post->reactedBy()->get();
    }

    // Get listt of dogs are tagged on the post with post_id = {id}
    public function getDogsTaggedInPost($post_id) {
        $post = \App\Post::find($post_id);
        return $post->taggedDogs()->get();
    }

    // Get list of comments of the post with post_id = {id}    
    public function getComment($post_id) {
        $post = \App\Post::find($post_id);
        return $post->comments()->get();
    }

    // Get list of dog's images having dog_id = {id}
    // Only the owner and who follows that dog can get, else return 403
    public function getDogImages(Request $request, $dog_id) {
        $user = $request->user();
        $dogs = $user->dogs()->get();
        foreach ($dogs as $one){
            if ($one->id == $dog_id){
                return $one->images()->get();
            }
        }
        $dog = \App\Dog::find($dog_id);
        $followed_users = $dog->followedBy()->get();
        foreach ($followed_users as $one){
            if ($one->id == $user->id){
                return $dog->images()->get();
            }
        }
        abort(403, "Unauthorized action.");
    }

    // Get list of tagable dogs
    public function getTaggableDogs(Request $request) {
        $user = $request->user();
        $friends = $user->friends()->where('status', '=', 'friend')->get();
        $theFriends = $user->theFriends()->where('status', '=', 'friend')->get();
        $dogs = [];
        foreach ($friends as $friend){
            $friend_dogs = $friend->dogs()->get();
            foreach ($friend_dogs as $dog){
                $dogs[] = $dog;
            }
        }
        foreach ($theFriends as $theFriend){
            $friend_dogs = $friend->dogs()->get();
            foreach ($friend_dogs as $dog){
                $dogs[] = $dog;
            }
        }
        return $dogs;
    }
}
