<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagParentOne extends Model
{
    public function tags(){
        return $this->hasMany('App\Tag', 'parent1', 'id');
    }
}
