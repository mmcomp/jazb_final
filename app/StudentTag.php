<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTag extends Model
{
    public function tag(){
        return $this->hasOne('App\Tag', 'id', 'tags_id');
    }
}
