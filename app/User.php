<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'created_at', 'updated_at', 'pivot',
    ];

    /**
     * Get user's all dogs
     * 
     * @return App\Dog
     */
    public function dogs(){
        return $this->hasMany('App\Dog', 'owner', 'id');
    }

    public function posts(){
        return $this->hasMany('App\Post', 'owner', 'id');
    }

    /**
     * Get all user's following dogs
     * 
     * @return App\Dog
     */
    public function follows(){
        return $this->belongsToMany('App\Dog', 'follows', 'user_id', 'dog_id');
    }
    
    /**
     * Get all user's friends
     * 
     * @return App\User
     */
    public function friends(){
        return $this->belongsToMany('App\User', 'friends', 'user_1', 'user_2')->withPivot('status');
    }

    public function theFriends(){
        return $this->belongsToMany('App\User', 'friends', 'user_2', 'user_1')->withPivot('status');
    }
}
