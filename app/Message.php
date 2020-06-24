<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function flows(){
        return $this->hasMany("App\MessageFlow", "messages_id", "id");
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'users_id');
    }
}
