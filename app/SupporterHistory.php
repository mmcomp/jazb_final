<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupporterHistory extends Model
{
    public function user(){
        return $this->hasOne('App\User', 'id', 'users_id');
    }
    public function student(){
        return $this->hasOne('App\Student', 'id', 'students_id');
    }
    public function supporter(){
        return $this->hasOne('App\Student', 'supporters_id', 'supporters_id');
    }
}
