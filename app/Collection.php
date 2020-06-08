<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public function parent(){
        return $this->hasOne('App\Collection', 'id', 'parent_id');
    }
}
