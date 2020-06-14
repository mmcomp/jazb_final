<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function user(){
        return $this->hasOne('App\User', 'id', 'users_id');
    }

    public function source(){
        return $this->hasOne('App\Source', 'id', 'sources_id');
    }

    public function studenttags(){
        return $this->hasMany('App\StudentTag', 'students_id', 'id');
    }

    public function studenttemperatures(){
        return $this->hasMany('App\StudentTemperature', 'students_id', 'id');
    }
}
