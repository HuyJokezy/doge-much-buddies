<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DogImage extends Model
{
    // Table name
    protected $table = 'dog_images';
    // Primary key
    public $primaryKey = 'id';
    // Timestamp
    public $timestamps = false;
    
    /**
     * Get the dog which image belongs to
     * 
     * @return App\Dog
     */
    public function dog(){
        return $this->belongsTo('App\Dog', 'id', 'dog_id');
    }
}
