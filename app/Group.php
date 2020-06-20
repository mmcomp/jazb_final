<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function gates(){
        return $this->hasMany('App\GroupGate', 'groups_id', 'id');
    }

    public static function getSupport(){
        return Group::where('type', 'support')->first();
    }

    public static function getConsultant(){
        return Group::where('type', 'consultant')->first();
    }
}
