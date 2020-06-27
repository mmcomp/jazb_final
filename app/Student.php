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
        return $this->hasMany('App\StudentTag', 'students_id', 'id')->where('is_deleted', false);
    }

    public function studenttemperatures(){
        return $this->hasMany('App\StudentTemperature', 'students_id', 'id')->where('is_deleted', false);
    }

    public function consultant(){
        return $this->hasOne('App\User', 'id', 'consultants_id');
    }

    public function supporter(){
        return $this->hasOne('App\User', 'id', 'supporters_id');
    }

    public function purchases(){
        return $this->hasMany('App\Purchase', 'students_id', 'id')->where('is_deleted', false);
    }

    public function studentcollections(){
        return $this->hasMany('App\StudentCollection', 'students_id', 'id')->where('is_deleted', false);
    }
}
