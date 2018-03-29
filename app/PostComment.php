<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    // Table name
    protected $table = 'post_comments';
    // Primary key
    public $primaryKey = 'id';
    // Timestamp
    public $timestamps = true;
    
    /**
     * Get user who created the comment
     * 
     * @return App\User
     */
    public function user(){
        return $this->belongsTo('App\User', 'id', 'owner');
    }

    /**
     * Get the post which comment belongs to 
     * 
     * @return App\Post
     */
    public function post(){
        return $this->belongsTo('App\Post', 'id', 'post_id');
    }
}
