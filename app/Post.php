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
        return $this->belongsTo('App\User');
    }

    /**
     * Get comments of the post
     * 
     * @return App\PostComment
     */
    public function comments(){
        return $this->hasMany('App\PostComment', 'post_id', 'id');
    }
}
