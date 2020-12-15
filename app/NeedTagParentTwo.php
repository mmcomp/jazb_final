<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NeedTagParentTwo extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'need_parent2', 'id');
    }
}
