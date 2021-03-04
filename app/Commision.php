<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commision extends Model
{
    protected $table = 'commission_supporter';
    protected $fillable = [
       'users_saver_id',
       'products_id',
       'users_id',
    ];
    public function product(){
        return $this->hasOne('App\Product','id','products_id');
    }
    public function user(){
        return $this->hasOne('App\User','id','users_id');
    }
}
