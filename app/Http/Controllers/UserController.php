<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    private function relationship (User $user, User $target) {
        $user_friends = $user->friends()->where('status', '=', 'friend')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return 'friend';
            }
        }
        $user_friends = $user->friends()->where('status', '=', 'pending')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return 'pending';
            }
        }
        $user_friends = $user->theFriends()->where('status', '=', 'friend')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return 'friend';
            }
        }
        $user_friends = $user->theFriends()->where('status', '=', 'pending')->get();
        foreach ($user_friends as $friend){
            if ($friend->id == $target->id) {
                return 'pending';
            }
        }

        return 'stranger';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($id == Auth::user()->id) {
            // return view('user.edit', ['user' => $user]);
            return $user;
        } else if ($this->relationship (Auth::user(), $user) == 'friend') {
            $user['relationship'] = 'friend';
            return $user;
        } else {
            // unset($user->id);
            unset($user->email);            
            unset($user->phone);
            unset($user->location);
            
            $user['relationship'] = $this->relationship (Auth::user(), $user);
            return $user;
        }
    }

    /**
     * Get user's dogs
     * 
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function myDog(Request $request, $id)
    {
        if ($id != Auth::user()->id){
            abort(403, "Unauthorized access.");
        }
        $dogs = User::find($id)->dogs;
        return view('user.dog')->with('dogs', $dogs);
        // return $dogs;
    }

    /**
     * Get user's friends
     * 
     * @param 
     * @return
     */
    public function myFriend(Request $request, $id)
    {
        if ($id != Auth::user()->id){
            abort(403, "Unauthorized access.");
        }
        $user = User::find($id);
        $list = [];
        $friends = $user->friends()->where('status', '=', 'friend')->get();
        foreach ($friends as $friend){
            $list[] = $friend;
        }
        $theFriends = $user->theFriends()->where('status', '=', 'friend')->get();
        foreach ($theFriends as $theFriend){
            $list[] = $theFriend;
        }
        // return $list;
        return view('user.friend', [
            'friends'=>$list
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        if (!isset($status)) $status = false;
        if ($id != Auth::user()->id){
            return redirect()->route('user.edit', ['id'=>Auth::user()->id]);
            abort(403, "Unauthorized access.");
        }
        return view('user.edit')->with('user', Auth::user());        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id != Auth::user()->id){
            abort(403, "Unauthorized access.");
        }
        $this->validate($request, [
            'name' => 'required|nullable|max:100',
            'location' => 'nullable|max:100',
            'phone' => 'nullable|max:100|regex:/^[0-9\-\+]{9,15}$/',
            'profileimg' => 'image|nullable|max:1999',
        ]);
        
        // Handle file upload
        if ($request->hasFile('profileimg')){
           // Get filename with extension
           $filenameWithExt = $request->file('profileimg')->getClientOriginalName();
           // Get just filename
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           // Get just extension
           $extension = $request->file('profileimg')->getClientOriginalExtension();
           // Filename to store
           $fileNameToStore = 'user_' . $id . '.' . $extension;
           // Upload image
           $path = $request->file('profileimg')->storeAs('public/user_profile/profileimgs', $fileNameToStore);
        }


        $user = User::find($id);
        $user->name = null !== $request->input('name') ? $request->input('name') : $user->name;
        $user->location = null !== $request->input('location') ? $request->input('location') : $user->location;
        $user->phone = null !== $request->input('phone') ? $request->input('phone') : $user->phone;
        if ($request->hasFile('profileimg')){
            $user->profile_image = $fileNameToStore;
        }
        $user->save();
        return redirect()->route('user.edit', [
            'id'=>Auth::user()->id
        ]);
        // return redirect('/user/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
