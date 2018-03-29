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
        return $this->belongsTo('App\User');
    }

    /**
     * Get the images of the dog
     * 
     * @return App\DogImage
     */
    public function images(){
        return $this->hasMany('App\DogImage');
    }
}
