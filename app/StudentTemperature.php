<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTemperature extends Model
{
    public function temperature(){
        return $this->hasOne('App\Tempartue', 'id', 'tempartures_id');
    }
}
