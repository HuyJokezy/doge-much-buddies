<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PostReact extends Model
{
    // Table name
    protected $table = 'post_reacts';
    // Primary key
    protected $primaryKey = ['owner', 'post_id'];
    // Incrementing
    public $incrementing = false;
    // Timestamp
    public $timestamps = false;
    
    /**
     * Get owner of the react
     * 
     * @return App\User
     */
    public function user(){
        return $this->belongsTo('App\User', 'id', 'owner');
    }

    /**
     * Get post which reaction belongs to
     * 
     * @return App\react
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
