<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Dog;
use Illuminate\Support\Facades\Auth;

class DogController extends Controller
{
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
        $dog = \App\Dog::find($id);
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
        //
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
        //
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
