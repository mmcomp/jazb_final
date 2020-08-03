<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagParentFour extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'parent4', 'id');
    }
}
