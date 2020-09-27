<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function province(){
        return $this->hasOne('App\Province', 'id', 'provinces_id');
    }
}
