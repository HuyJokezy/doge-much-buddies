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
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function post(){
        return $this->belongsTo('App\Post');
    }
}
