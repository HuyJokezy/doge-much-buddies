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
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
