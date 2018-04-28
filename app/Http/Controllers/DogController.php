<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Dog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DogController extends Controller
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
     * Check right access on editing dog
     * 
     * @param object user
     * @param int dog_id
     * @return true on having access, false otherwise
     */
    private function checkAccess($user, $dog_id) {
        $dog = Dog::find($dog_id);
        if ($user->id != $dog->owner) {
            return false;
        }
        return true;
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
        return view('dog.create');
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
            'name' => 'required|max:100',
            'breed' => 'required|in:Mixed,Labrador Retriever,German Shepherds,Golden Retriever,Bulldog,Boxer,Rottweiler,Dachshund,Husky,Great Dane,Doberman Pinschers,Australian Shepherds,Corgi,Shiba|max:100',
            'gender' => 'required|in:Male,Female',
            'profileimg' => 'image|nullable|max:1999',
        ]);
        
        $statement = DB::select("SHOW TABLE STATUS LIKE 'dogs'");
        $id = $statement[0]->Auto_increment;

        // Handle file upload
        if ($request->hasFile('profileimg')){
           // Get filename with extension
           $filenameWithExt = $request->file('profileimg')->getClientOriginalName();
           // Get just filename
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           // Get just extension
           $extension = $request->file('profileimg')->getClientOriginalExtension();
           // Filename to store
           $fileNameToStore = 'dog_' . $id . '_user_' . $user->id . '.' . $extension;
           // Upload image
           $path = $request->file('profileimg')->storeAs('public/dogs/', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $dog = new Dog;
        $dog->name = $request->input('name');
        $dog->owner = $user->id;
        $dog->breed = $request->input('breed');
        $dog->gender = $request->input('gender');
        $dog->profile_image = 'dogs/' . $fileNameToStore;

        $dog->save();
        return redirect('/dog/' . $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $dogs = $user->dogs()->get();
        foreach ($dogs as $one){
            if ($one->id == $id){
                $result = array(
                    'information' => $one,
                    'images' => $one->images()->get(),
                );
                return $result;
            }
        }
        $dog = Dog::find($id);
        $followed_users = $dog->followedBy()->get();
        foreach ($followed_users as $one){
            if ($one->id == $user->id){
                $result = array(
                    'information' => $dog,
                    'images' => $dog->images()->get(),
                );
                return $result;
            }
        }
        abort(403, "Unauthorized action.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ($this->checkAccess($user, $id) == false){
            abort(403, "Unauthorized access.");
        }

        return view('dog.edit')->with('dog', Dog::find($id));
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
        $user = $request->user();
        
        if ($this->checkAccess($user, $id) == false) {
            abort(403, "Unauthorized access.");
        }

        $this->validate($request, [
            'name' => 'required|nullable|max:100',
            'breed' => 'required|nullable|in:Mixed,Labrador Retriever,German Shepherds,Golden Retriever,Bulldog,Boxer,Rottweiler,Dachshund,Husky,Great Dane,Doberman Pinschers,Australian Shepherds,Corgi,Shiba|max:100',
            'gender' => 'required|nullable|in:Male,Female',
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
           $fileNameToStore = 'dog_' . $id . '_user_' . $user->id . '.' . $extension;
           // Upload image
           $path = $request->file('profileimg')->storeAs('public/dogs/', $fileNameToStore);

           if ($dog->profile_image != 'dogs/noimage.jpg') {
                Storage::delete('public/' . $dog->profile_image);
           }
        }

        $dog = Dog::find($id);
        $dog->name = null !== $request->input('name') ? $request->input('name') : $dog->name;
        $dog->breed = null !== $request->input('breed') ? $request->input('breed') : $dog->name;
        $dog->gender = null !== $request->input('gender') ? $request->input('gender') : $dog->name;
        if ($request->hasFile('profileimg')) {
            $dog->profile_image = 'dogs/' . $fileNameToStore;
        }

        $dog->save();
        return redirect('/dog/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($this->checkAccess($user, $id) == false){
            abort(403, "Unauthorized access.");
        }
        $dog = Dog::find($id);

        if ($dog->profile_image != 'dogs/noimage.jpg'){
            Storage::delete('public/' . $dog->profile_image);
        }
        $dog->delete();
        return redirect('/user/' . $user->id . '/myDog')->with('success', 'You abadoned your dog :(');
    }
}
