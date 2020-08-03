<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagParentTwo extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'parent2', 'id');
    }
}
