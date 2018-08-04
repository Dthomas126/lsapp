<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //All is saying that a single post belongs to a user

    //Table name
    protected $table = 'posts';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }

}
