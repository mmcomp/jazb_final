<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marketer extends Model
{
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'users_id');
    }
}
