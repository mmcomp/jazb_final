<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    public function product(){
        return $this->hasOne('App\Product', 'id', 'products_id');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'users_id');
    }
}
