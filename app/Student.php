<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'egucation_level',
        'parents_job_title',
        'home_phone',
        'father_phone',
        'mother_phone',
        'school',
        'average' ,
        'major',
        'introducing',
        'student_phone',
        'citys_id',
        'sources_id',
        'supporters_id'
    ];

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

    public function calls(){
        return $this->hasMany('App\Call', 'students_id', 'id')->orderBy('created_at', 'desc');
    }

    public function remindercalls(){
        return $this->hasMany('App\Call', 'students_id', 'id')->where('next_call', '!=', null);
    }


    public function studentclasses(){
        return $this->hasMany('App\StudentClassRoom', 'students_id', 'id');
    }
}
