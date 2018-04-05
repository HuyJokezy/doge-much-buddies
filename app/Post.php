<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table name
    protected $table = 'posts';
    // Primary key
    public $primaryKey = 'id';
    // Timestamp
    public $timestamps = true;
    
    /**
     * Get user who created post
     * 
     * @return App\User
     */
    public function user(){
        return $this->belongsTo('App\User', 'id', 'owner');
    }

    /**
     * Get comments of the post
     * 
     * @return App\PostComment
     */
    public function comments(){
        return $this->hasMany('App\PostComment', 'post_id', 'id');
    }

    /**
     * Get all users who react on post
     * 
     * @return App\User
     */
    public function reactedBy(){
        return $this->belongsToMany('App\User', 'post_reacts', 'post_id', 'owner')->withPivot('type');
    }

    /**
     * Get all dogs tagged on the post
     * 
     * @return App\User
     */
    public function taggedDogs(){
        return $this->belongsToMany('App\Dog', 'post_tags', 'post_id', 'dog_id');
    }
}
