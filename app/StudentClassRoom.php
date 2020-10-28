<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClassRoom extends Model
{
    public function student(){
        return $this->hasOne('App\Studnet', 'id', 'students_id');
    }

    public function class(){
        return $this->hasOne('App\ClassRoom', 'id', 'class_rooms_id');
    }
}
