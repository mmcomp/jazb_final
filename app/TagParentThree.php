<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagParentThree extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'parent3', 'id');
    }
}
