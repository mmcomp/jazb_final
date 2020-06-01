<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function parent(){
        return $this->hasOne('App\Tag', 'id', 'parent_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'users_id');
    }
}
