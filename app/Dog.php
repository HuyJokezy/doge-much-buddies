<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    // Table name
    protected $table = 'dogs';
    // Primary key
    public $primaryKey = 'id';
    // Timestamp
    public $timestamps = true;
    
    /**
     * Get the owner of the dog
     * 
     * @return App\User
     */
    public function user(){
        return $this->belongsTo('App\User', 'id', 'owner');
    }

    /**
     * Get the images of the dog
     * 
     * @return App\DogImage
     */
    public function images(){
        return $this->hasMany('App\DogImage', 'dog_id', 'id');
    }

    /**
     * Get user's follow mapping
     * 
     * @return App\Follow
     */
    public function followedBy(){
        return $this->belongsToMany('App\User', 'follows', 'dog_id', 'user_id');
    }
    
    /**
     * Get all posts that tag this dog
     * 
     * @return App\User
     */
    public function taggedIn(){
        return $this->belongsToMany('App\Post', 'post_tags', 'dog_id', 'post_id');
    }
}
