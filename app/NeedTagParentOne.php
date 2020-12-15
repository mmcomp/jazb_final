<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NeedTagParentOne extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'need_parent1', 'id');
    }
}
