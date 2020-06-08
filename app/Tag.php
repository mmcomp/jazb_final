<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function parent(){
        return $this->hasOne('App\Tag', 'id', 'parent_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'users_id');
    }

    public function parent_one(){
        return $this->hasOne('App\TagParentOne', 'id', 'parent1');
    }

    public function parent_two(){
        return $this->hasOne('App\TagParentTwo', 'id', 'parent2');
    }

    public function parent_three(){
        return $this->hasOne('App\TagParentThree', 'id', 'parent3');
    }

    public function parent_four(){
        return $this->hasOne('App\TagParentFour', 'id', 'parent4');
    }
}
