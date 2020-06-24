<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function user(){
        return $this->hasOne('App\User', 'id', 'users_id');
    }

    public function student(){
        return $this->hasOne('App\Student', 'id', 'students_id');
    }

    public function product(){
        return $this->hasOne('App\Product', 'id', 'products_id');
    }
}
