<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function gates(){
        return $this->hasMany('App\GroupGate', 'groups_id', 'id');
    }
}
