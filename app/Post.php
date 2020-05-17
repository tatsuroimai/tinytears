<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{
    protected $guarded = array('id');

    public function user(){
        return $this->belongsTo('App\User');
    }
}
