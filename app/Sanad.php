<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sanad extends Model
{
    public function supporter()
    {
        return $this->hasOne('App\User', 'id', 'supporter_id');
    }
}
