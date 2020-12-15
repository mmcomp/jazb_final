<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NeedTagParentThree extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'need_parent3', 'id');
    }
}
