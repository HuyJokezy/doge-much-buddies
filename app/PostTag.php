<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    // Table name
    protected $table = 'posts';
    // Primary key
    protected $primaryKey = ['post_id', 'dog_id'];
    public $incrementing = false;
    // Timestamp
    public $timestamps = true;
    
    /**
     * Get dog that tagged in the post
     */
    public function dogTag(){
        return $this->hasMany('App\Dog', 'id', 'dog_id');
    }

    /**
     * Get post 
     * 
     * @return App\Post
     */
    public function post(){
        return $this->belongsTo('App\Post', 'id', 'post_id');
    }


    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
