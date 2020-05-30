<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    public function user()
    {
        return $this->hasOne('App\User');
    }
}
