<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NeedTagParentFour extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'need_parent4', 'id');
    }
}
