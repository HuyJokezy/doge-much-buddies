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
        'password', 'remember_token', 'api_token',
    ];

    /**
     * Get user's all dogs
     * 
     * @return App\Dog
     */
    public function dogs(){
        return $this->hasMany('App\Dog', 'owner', 'id');
    }

    /**
     * Get all user's following dogs
     * 
     * @return App\Dog
     */
    public function follows(){
        return $this->belongsToMany('App\Dog', 'follows', 'user_id', 'dog_id');
    }
}
